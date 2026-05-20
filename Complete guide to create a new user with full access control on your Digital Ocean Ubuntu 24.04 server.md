Here's a complete guide to create a new user with full access control on your Digital Ocean Ubuntu 24.04 server:

## Step 1: Create the new user

```bash
sudo adduser adminuser
password: Angkorsr@sa168$$kh
```

Replace `adminuser` with your desired username (e.g., `superadmin`). You'll be prompted to set a password and optionally fill in user information.

## Step 2: Add user to sudo group (root-like privileges)

```bash
sudo usermod -aG sudo adminuser
```

This gives the user the ability to run commands as root using `sudo`.

## Step 3: Configure /var/www/ access

You have several options:

### Option A: Make the user owner of /var/www/ (Simple, good for single developer)

```bash
sudo chown -R adminuser:www-data /var/www/
sudo chmod -R 775 /var/www/
```

### Option B: Add user to www-data group (Good for multiple users)

```bash
sudo usermod -aG www-data adminuser
sudo chown -R www-data:www-data /var/www/
sudo chmod -R 775 /var/www/
```

### Option C: Use ACLs for granular control (Best for production)

```bash
sudo apt update
sudo apt install acl -y
sudo setfacl -R -m u:adminuser:rwx /var/www/
sudo setfacl -R -d -m u:adminuser:rwx /var/www/
```

## Step 4: Set proper permissions for future files

To ensure new files created in `/var/www/` have correct permissions:

```bash
sudo chmod -R g+s /var/www/
```

This sets the setgid bit, so new files inherit the group ownership.

## Step 5: Switch to the new user and test

```bash
su - adminuser
cd /var/www/
touch test.txt
```

If you can create the file without using `sudo`, the permissions are working correctly.

## Step 6: Enable SSH access (if needed)

If you want to login via SSH with this user:

```bash
# As root or with sudo
sudo mkdir -p /home/adminuser/.ssh
sudo chmod 700 /home/adminuser/.ssh
sudo chown adminuser:adminuser /home/adminuser/.ssh
```

Then add your SSH public key:

```bash
sudo nano /home/adminuser/.ssh/authorized_keys
# Paste your public key, save and exit
sudo chmod 600 /home/adminuser/.ssh/authorized_keys
sudo chown adminuser:adminuser /home/adminuser/.ssh/authorized_keys
```

## Complete example with a user called "superadmin":

```bash
# Create user
sudo adduser superadmin

# Give sudo privileges
sudo usermod -aG sudo superadmin

# Give access to /var/www/
sudo chown -R superadmin:www-data /var/www/
sudo chmod -R 775 /var/www/
sudo chmod -R g+s /var/www/

# Verify
ls -la /var/www/
```

## Important security note:

After Option B, the user needs to **log out and log back in** for group membership changes to take effect. You can verify group membership with:

```bash
groups adminuser
```

This setup gives your user root-like privileges through sudo while maintaining proper web server permissions. Would you like me to explain any of these steps in more detail?
