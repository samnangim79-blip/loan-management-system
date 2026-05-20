#!/usr/bin/env python3
"""
Laravel Blade Translation Automation Script
Scans all Blade template files and automatically converts hardcoded text to translation keys
Updates language files with the new translation keys
"""

import os
import re
import json
import sys
from pathlib import Path
from collections import defaultdict
from typing import Dict, List, Set, Tuple

class BladeTranslationUpdater:
    def __init__(self, project_root: str):
        self.project_root = Path(project_root)
        self.views_path = self.project_root / 'resources' / 'views'
        self.lang_path = self.project_root / 'lang'

        # Language files to update
        self.languages = ['en', 'kh', 'zh']

        # Translation sections to organize keys
        self.sections = {
            'general': ['list', 'add', 'edit', 'delete', 'view', 'save', 'cancel', 'close', 'back', 'next', 'previous', 'submit', 'update', 'create', 'search', 'filter', 'reset', 'print', 'export', 'action', 'actions', 'status', 'name', 'email', 'phone', 'address', 'date', 'amount', 'type', 'description', 'remarks', 'id', 'no', 'yes'],
            'nav': ['dashboard', 'home', 'customers', 'accounts', 'loans', 'reports', 'settings', 'logout', 'profile'],
            'form': ['required', 'optional', 'select', 'choose', 'enter', 'upload', 'browse', 'clear'],
            'messages': ['success', 'error', 'warning', 'info', 'loading', 'saved', 'deleted', 'updated', 'created'],
            'auth': ['login', 'register', 'password', 'email', 'remember', 'forgot'],
            'pagination': ['first', 'last', 'previous', 'next', 'showing', 'entries', 'of', 'to']
        }

        # Patterns to identify translatable text
        self.text_patterns = [
            # Button text (but not in attributes)
            r'<button[^>]*>(?:\s*<i[^>]*></i>\s*)?([A-Z][A-Za-z\s]+)(?:\s*<[^>]*>\s*)?</button>',
            # Link text (but not in href or other attributes)
            r'<a[^>]*>(?:\s*<i[^>]*></i>\s*)?([A-Z][A-Za-z\s]+)(?:\s*<[^>]*>\s*)?</a>',
            # Label text (but not in for attributes)
            r'<label[^>]*>([A-Z][A-Za-z\s:]+)(?:\s*<span[^>]*>\s*\*\s*</span>)?</label>',
            # th/td content (but not in class attributes)
            r'<th[^>]*>([A-Z][A-Za-z\s]+)</th>',
            # h1-h6 headings
            r'<h[1-6][^>]*>([A-Z][A-Za-z\s]+)</h[1-6]>',
            # p tags with simple text
            r'<p[^>]*>([A-Z][A-Za-z\s]+)</p>',
            # option text
            r'<option[^>]*>([A-Z][A-Za-z\s]+)</option>',
            # JavaScript strings (be more careful)
            r'(?:text|title|message|label):\s*[\'"]([A-Z][A-Za-z\s,!?.-]+)[\'"]',
        ]

        # Existing translation patterns to avoid
        self.existing_translation_pattern = r'\{\{\s*__\([\'"][^\'"]+[\'"]\)\s*\}\}'

        # Files to skip
        self.skip_files = {'.gitignore', 'README.md'}

        # Words/patterns that should NEVER be translated
        self.exclude_patterns = {
            # Technical terms
            'stylesheet', 'preconnect', 'icon', 'shortcut icon', 'viewport',
            # CSS classes
            'pt-main-wrapper', 'pt-content-wrapper', 'd-flex', 'align-items-center',
            'justify-content-end', 'rounded-circle', 'btn', 'fa-solid', 'fa-bars',
            'fa-expand', 'fa-lg',
            # HTML attributes
            'rel', 'href', 'class', 'id', 'name', 'type', 'content', 'width', 'height',
            # JavaScript/CSS identifiers
            'fullscreenBtn', 'styles', 'accountForm', 'accountId',
            # File paths and routes
            'accounts.create', 'accounts.edit', 'components.language-switcher',
            # Brand names
            'Panha Tech', 'Bootstrap', 'FontAwesome', 'Laravel',
            # Technical abbreviations
            'CSS', 'JS', 'HTML', 'API', 'URL', 'HTTP', 'HTTPS'
        }

        # Discovered translations
        self.discovered_translations = defaultdict(dict)

        # Translation mappings for common terms
        self.common_translations = {
            'en': {
                'Add': 'Add',
                'Edit': 'Edit',
                'Delete': 'Delete',
                'View': 'View',
                'Save': 'Save',
                'Cancel': 'Cancel',
                'Close': 'Close',
                'Back': 'Back',
                'Next': 'Next',
                'Previous': 'Previous',
                'Submit': 'Submit',
                'Update': 'Update',
                'Create': 'Create',
                'Search': 'Search',
                'Filter': 'Filter',
                'Reset': 'Reset',
                'Action': 'Action',
                'Actions': 'Actions',
                'Status': 'Status',
                'Name': 'Name',
                'Email': 'Email',
                'Phone': 'Phone',
                'Address': 'Address',
                'Date': 'Date',
                'Amount': 'Amount',
                'Type': 'Type',
                'Description': 'Description',
                'ID': 'ID',
                'No': 'No',
                'Yes': 'Yes',
                'Required': 'Required',
                'Optional': 'Optional',
                'Select': 'Select',
                'Choose': 'Choose',
                'Loading': 'Loading...',
                'Success': 'Success',
                'Error': 'Error',
                'Warning': 'Warning',
                'Information': 'Information'
            },
            'kh': {
                'Add': 'បន្ថែម',
                'Edit': 'កែសម្រួល',
                'Delete': 'លុប',
                'View': 'មើល',
                'Save': 'រក្សាទុក',
                'Cancel': 'បោះបង់',
                'Close': 'បិទ',
                'Back': 'ត្រឡប់',
                'Next': 'បន្ទាប់',
                'Previous': 'មុន',
                'Submit': 'ដាក់ស្នើ',
                'Update': 'ធ្វើបច្ចុប្បន្នភាព',
                'Create': 'បង្កើត',
                'Search': 'ស្វែងរក',
                'Filter': 'តម្រង',
                'Reset': 'កំណត់ឡើងវិញ',
                'Action': 'សកម្មភាព',
                'Actions': 'សកម្មភាព',
                'Status': 'ស្ថានភាព',
                'Name': 'ឈ្មោះ',
                'Email': 'អ៊ីមែល',
                'Phone': 'ទូរស័ព្ទ',
                'Address': 'អាសយដ្ឋាន',
                'Date': 'កាលបរិច្ឆេទ',
                'Amount': 'ចំនួន',
                'Type': 'ប្រភេទ',
                'Description': 'ការពិពណ៌នា',
                'ID': 'អត្តសញ្ញាណ',
                'No': 'ទេ',
                'Yes': 'បាទ/ចាស',
                'Required': 'ចាំបាច់',
                'Optional': 'ស្រេចចិត្ត',
                'Select': 'ជ្រើសរើស',
                'Choose': 'ជ្រើសរើស',
                'Loading': 'កំពុងផ្ទុក...',
                'Success': 'ជោគជយ',
                'Error': 'កំហុស',
                'Warning': 'ការព្រមាន',
                'Information': 'ព័ត៌មាន'
            },
            'zh': {
                'Add': '添加',
                'Edit': '编辑',
                'Delete': '删除',
                'View': '查看',
                'Save': '保存',
                'Cancel': '取消',
                'Close': '关闭',
                'Back': '返回',
                'Next': '下一页',
                'Previous': '上一页',
                'Submit': '提交',
                'Update': '更新',
                'Create': '创建',
                'Search': '搜索',
                'Filter': '过滤',
                'Reset': '重置',
                'Action': '操作',
                'Actions': '操作',
                'Status': '状态',
                'Name': '姓名',
                'Email': '邮箱',
                'Phone': '电话',
                'Address': '地址',
                'Date': '日期',
                'Amount': '金额',
                'Type': '类型',
                'Description': '描述',
                'ID': 'ID',
                'No': '否',
                'Yes': '是',
                'Required': '必填',
                'Optional': '可选',
                'Select': '选择',
                'Choose': '选择',
                'Loading': '加载中...',
                'Success': '成功',
                'Error': '错误',
                'Warning': '警告',
                'Information': '信息'
            }
        }

    def clean_text(self, text: str) -> str:
        """Clean and normalize text"""
        # Remove HTML tags
        text = re.sub(r'<[^>]+>', '', text)
        # Remove extra whitespace
        text = re.sub(r'\s+', ' ', text).strip()
        # Remove special characters at the end
        text = re.sub(r'[:\s]*$', '', text)

        # Check if text should be excluded
        if text.lower() in [p.lower() for p in self.exclude_patterns]:
            return ''  # Return empty to skip translation

        return text

    def generate_translation_key(self, text: str, context: str = '') -> str:
        """Generate a translation key from text"""
        # Clean the text
        clean = self.clean_text(text)

        # Convert to snake_case
        key = re.sub(r'[^a-zA-Z0-9\s]', '', clean)
        key = re.sub(r'\s+', '_', key.lower())

        # Determine section based on context and text
        section = self.determine_section(clean, context)

        return f'common.{section}.{key}'

    def determine_section(self, text: str, context: str) -> str:
        """Determine which section a translation belongs to"""
        text_lower = text.lower()

        # Check each section for common words
        for section, keywords in self.sections.items():
            if any(keyword in text_lower for keyword in keywords):
                return section

        # Context-based detection
        if 'nav' in context or 'menu' in context:
            return 'nav'
        elif 'form' in context or 'input' in context:
            return 'form'
        elif 'button' in context:
            return 'general'
        elif 'modal' in context:
            return 'general'
        elif 'table' in context:
            return 'general'

        # Default to general
        return 'general'

    def scan_blade_file(self, file_path: Path) -> Dict[str, List[Tuple[str, str, str]]]:
        """Scan a single Blade file for translatable text"""
        translations_found = defaultdict(list)

        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()

            # Skip if already has translations
            existing_translations = re.findall(self.existing_translation_pattern, content)
            if len(existing_translations) > 5:  # Already heavily translated
                print(f"  ✓ {file_path.name} - Already translated ({len(existing_translations)} translations found)")
                return translations_found

            # Find translatable text using patterns
            for pattern in self.text_patterns:
                matches = re.finditer(pattern, content, re.IGNORECASE)
                for match in matches:
                    text = match.group(1).strip()

                    # Skip if too short, too long, or contains special chars
                    if (len(text) < 2 or len(text) > 50 or
                        re.search(r'[{}$@#%^&*()]', text) or
                        text.isdigit() or
                        text.lower() in ['id', 'no', 'ok']):
                        continue

                    # Clean and generate key
                    clean_text = self.clean_text(text)
                    if clean_text:
                        context = self.get_context(content, match.start())
                        translation_key = self.generate_translation_key(clean_text, context)

                        # Store found translation
                        translations_found[translation_key].append((
                            clean_text,
                            match.group(0),  # Original match
                            context
                        ))

        except Exception as e:
            print(f"  ✗ Error scanning {file_path}: {e}")

        return translations_found

    def get_context(self, content: str, position: int) -> str:
        """Get context around a match position"""
        # Look for surrounding tags
        start = max(0, position - 200)
        end = min(len(content), position + 200)
        context_snippet = content[start:end]

        # Find HTML tags or common patterns
        context_indicators = []
        if '<button' in context_snippet:
            context_indicators.append('button')
        if '<a ' in context_snippet or '<a>' in context_snippet:
            context_indicators.append('link')
        if '<label' in context_snippet:
            context_indicators.append('form')
        if '<th' in context_snippet or '<td' in context_snippet:
            context_indicators.append('table')
        if 'modal' in context_snippet:
            context_indicators.append('modal')
        if 'nav' in context_snippet or 'menu' in context_snippet:
            context_indicators.append('nav')

        return '_'.join(context_indicators) if context_indicators else 'general'

    def scan_all_views(self) -> Dict[str, Dict[str, List[Tuple[str, str, str]]]]:
        """Scan all Blade template files"""
        all_translations = {}

        print(f"🔍 Scanning views directory: {self.views_path}")

        for root, dirs, files in os.walk(self.views_path):
            for file in files:
                if file.endswith('.blade.php') and file not in self.skip_files:
                    file_path = Path(root) / file
                    relative_path = file_path.relative_to(self.views_path)

                    print(f"  📄 Scanning {relative_path}")
                    translations = self.scan_blade_file(file_path)

                    if translations:
                        all_translations[str(relative_path)] = translations
                        print(f"    Found {len(translations)} unique translation keys")
                    else:
                        print(f"    No new translations needed")

        return all_translations

    def extract_section_from_key(self, key: str) -> str:
        """Extract section from translation key"""
        if key.startswith('common.'):
            parts = key.split('.')
            if len(parts) >= 2:
                return parts[1]
        return 'general'

    def extract_key_name(self, key: str) -> str:
        """Extract key name from translation key"""
        parts = key.split('.')
        return parts[-1] if parts else key

    def load_existing_translations(self, lang: str) -> Dict[str, any]:
        """Load existing translations from language file"""
        lang_file = self.lang_path / lang / 'common.php'

        if not lang_file.exists():
            print(f"  ⚠️ Language file not found: {lang_file}")
            return {}

        try:
            # Read the PHP array file (simplified parsing)
            with open(lang_file, 'r', encoding='utf-8') as f:
                content = f.read()

            # Extract existing translations using regex
            existing = {}

            # Look for sections like 'general' => [...], 'nav' => [...]
            section_pattern = r"'(\w+)'\s*=>\s*\[(.*?)\],"
            sections = re.finditer(section_pattern, content, re.DOTALL)

            for section_match in sections:
                section_name = section_match.group(1)
                section_content = section_match.group(2)

                # Extract key-value pairs within section
                key_pattern = r"'([^']+)'\s*=>\s*'([^']*)',"
                keys = re.findall(key_pattern, section_content)

                section_dict = {}
                for key, value in keys:
                    section_dict[key] = value

                existing[section_name] = section_dict

            return existing

        except Exception as e:
            print(f"  ✗ Error loading {lang_file}: {e}")
            return {}

    def update_language_files(self, all_translations: Dict[str, Dict[str, List[Tuple[str, str, str]]]]):
        """Update language files with new translations"""
        print(f"\n📝 Updating language files...")

        # Collect all unique translation keys
        all_keys = {}
        for file_translations in all_translations.values():
            for key, text_list in file_translations.items():
                if key not in all_keys:
                    # Use the first (and usually best) translation text
                    all_keys[key] = text_list[0][0]

        print(f"  Found {len(all_keys)} unique translation keys to add/update")

        # Update each language
        for lang in self.languages:
            print(f"\n  📄 Updating {lang} translations...")

            # Load existing translations
            existing = self.load_existing_translations(lang)

            # Organize new translations by section
            sections_to_update = defaultdict(dict)

            for key, text in all_keys.items():
                section = self.extract_section_from_key(key)
                key_name = self.extract_key_name(key)

                # Get translation based on language
                if lang in self.common_translations and text in self.common_translations[lang]:
                    translated_text = self.common_translations[lang][text]
                elif lang == 'en':
                    translated_text = text
                else:
                    # For non-English languages, use placeholder or keep English
                    translated_text = f"[{text}]"  # Placeholder for manual translation

                sections_to_update[section][key_name] = translated_text

            # Merge with existing
            for section, new_keys in sections_to_update.items():
                if section not in existing:
                    existing[section] = {}
                existing[section].update(new_keys)

            # Write updated language file
            self.write_language_file(lang, existing)

            print(f"    ✓ Updated {sum(len(keys) for keys in sections_to_update.values())} keys")

    def write_language_file(self, lang: str, translations: Dict[str, Dict[str, str]]):
        """Write translations to language file"""
        lang_file = self.lang_path / lang / 'common.php'
        lang_file.parent.mkdir(parents=True, exist_ok=True)

        # Generate PHP array content
        content = "<?php\n\nreturn [\n"

        # Add general information comment
        content += "\n    /*\n"
        content += "    |--------------------------------------------------------------------------\n"
        content += "    | Common Language Lines\n"
        content += "    |--------------------------------------------------------------------------\n"
        content += "    |\n"
        content += "    | Auto-generated translations for common UI elements\n"
        content += "    |\n"
        content += "    */\n\n"

        # Write each section
        for section_name in ['general', 'nav', 'form', 'messages', 'auth', 'pagination', 'customer_management', 'account_management', 'loan_management', 'transaction_management', 'reports']:
            if section_name in translations and translations[section_name]:
                content += f"    // {section_name.replace('_', ' ').title()}\n"
                content += f"    '{section_name}' => [\n"

                for key, value in sorted(translations[section_name].items()):
                    # Escape single quotes in values
                    escaped_value = value.replace("'", "\\'")
                    content += f"        '{key}' => '{escaped_value}',\n"

                content += "    ],\n\n"

        content += "];\n"

        # Write to file
        with open(lang_file, 'w', encoding='utf-8') as f:
            f.write(content)

    def update_blade_files(self, all_translations: Dict[str, Dict[str, List[Tuple[str, str, str]]]]):
        """Update Blade files with translation keys"""
        print(f"\n✏️ Updating Blade template files...")

        for file_path, translations in all_translations.items():
            full_path = self.views_path / file_path
            print(f"  📄 Updating {file_path}")

            try:
                with open(full_path, 'r', encoding='utf-8') as f:
                    content = f.read()

                original_content = content
                updates_made = 0

                # Replace each found text with translation key
                for key, text_list in translations.items():
                    for text, original_match, context in text_list:
                        # Create replacement with translation function
                        replacement = f"{{{{ __('{key}') }}}}"

                        # Handle different contexts
                        if 'button' in context and '<button' in original_match:
                            # Keep the button structure, just replace text
                            new_match = re.sub(r'>([^<]+)<', f'>{replacement}<', original_match)
                        elif 'link' in context and '<a ' in original_match:
                            # Keep the link structure, just replace text
                            new_match = re.sub(r'>([^<]+)<', f'>{replacement}<', original_match)
                        else:
                            # Simple text replacement
                            new_match = original_match.replace(text, replacement)

                        # Replace in content if not already translated
                        if original_match in content and '{{' not in original_match:
                            content = content.replace(original_match, new_match)
                            updates_made += 1

                # Write updated content
                if updates_made > 0:
                    with open(full_path, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"    ✓ Made {updates_made} updates")
                else:
                    print(f"    - No updates needed")

            except Exception as e:
                print(f"    ✗ Error updating {file_path}: {e}")

    def generate_summary_report(self, all_translations: Dict[str, Dict[str, List[Tuple[str, str, str]]]]):
        """Generate a summary report of the translation process"""
        print(f"\n📊 TRANSLATION SUMMARY REPORT")
        print(f"{'='*50}")

        total_files = len(all_translations)
        total_keys = sum(len(translations) for translations in all_translations.values())
        total_texts = sum(len(text_list) for translations in all_translations.values()
                         for text_list in translations.values())

        print(f"📁 Files processed: {total_files}")
        print(f"🔑 Unique translation keys: {total_keys}")
        print(f"📝 Total text instances: {total_texts}")
        print(f"🌍 Languages updated: {', '.join(self.languages)}")

        print(f"\n📋 Files with translations:")
        for file_path, translations in all_translations.items():
            key_count = len(translations)
            text_count = sum(len(text_list) for text_list in translations.values())
            print(f"  📄 {file_path:<40} {key_count:>3} keys, {text_count:>3} instances")

        print(f"\n🔤 Translation keys by section:")
        section_counts = defaultdict(int)
        for translations in all_translations.values():
            for key in translations.keys():
                section = self.extract_section_from_key(key)
                section_counts[section] += 1

        for section, count in sorted(section_counts.items()):
            print(f"  📂 {section:<20} {count:>3} keys")

        print(f"\n✅ Translation automation completed successfully!")
        print(f"🔧 Next steps:")
        print(f"   1. Review generated translation keys in lang/ files")
        print(f"   2. Update non-English translations (marked with [brackets])")
        print(f"   3. Test the updated views in your application")
        print(f"   4. Run 'php artisan config:clear' to refresh translations")

    def run(self):
        """Main execution method"""
        print(f"🚀 Laravel Blade Translation Automation Tool")
        print(f"📁 Project: {self.project_root}")
        print(f"🌍 Languages: {', '.join(self.languages)}")

        # Check if paths exist
        if not self.views_path.exists():
            print(f"❌ Views directory not found: {self.views_path}")
            return False

        if not self.lang_path.exists():
            print(f"📁 Creating language directory: {self.lang_path}")
            self.lang_path.mkdir(parents=True, exist_ok=True)

        # Scan all view files
        all_translations = self.scan_all_views()

        if not all_translations:
            print(f"\n✅ No new translations needed. All files appear to be already translated.")
            return True

        # Update language files
        self.update_language_files(all_translations)

        # Update Blade files
        self.update_blade_files(all_translations)

        # Generate report
        self.generate_summary_report(all_translations)

        return True

def main():
    """Main function"""
    if len(sys.argv) > 1:
        project_root = sys.argv[1]
    else:
        project_root = os.getcwd()

    updater = BladeTranslationUpdater(project_root)
    success = updater.run()

    if success:
        print(f"\n🎉 Translation automation completed successfully!")
    else:
        print(f"\n❌ Translation automation failed!")
        sys.exit(1)

if __name__ == "__main__":
    main()
