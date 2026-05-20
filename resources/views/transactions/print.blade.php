<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction Receipt - {{ $transaction->tran_id }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      color: #333;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #333;
      padding-bottom: 20px;
      margin-bottom: 30px;
    }

    .company-name {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .receipt-title {
      font-size: 18px;
      margin-top: 15px;
    }

    .transaction-info {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .info-section {
      width: 48%;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      padding: 5px 0;
      border-bottom: 1px dotted #ccc;
    }

    .info-label {
      font-weight: bold;
      width: 40%;
    }

    .info-value {
      width: 58%;
      text-align: right;
    }

    .amount-highlight {
      font-size: 20px;
      font-weight: bold;
      color: #2563eb;
      background: #f0f9ff;
      padding: 10px;
      text-align: center;
      border: 2px solid #2563eb;
      margin: 20px 0;
    }

    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 12px;
      color: #666;
      border-top: 1px solid #ccc;
      padding-top: 20px;
    }

    .signature-section {
      margin-top: 40px;
      display: flex;
      justify-content: space-between;
    }

    .signature-box {
      width: 30%;
      text-align: center;
      border-top: 1px solid #333;
      padding-top: 10px;
      margin-top: 50px;
    }

    @media print {
      body {
        margin: 0;
      }

      .no-print {
        display: none;
      }
    }

    .status-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: bold;
    }

    .status-approved {
      background-color: #d4edda;
      color: #155724;
    }

    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }
  </style>
</head>

<body>
  <div class="header">
    <div class="company-name">Loan Management System</div>
    <div>{{ $transaction->branch->branch_name ?? 'Main Branch' }}</div>
    <div>{{ $transaction->branch->phone ?? '+855-23-123456' }}</div>
    <div class="receipt-title">TRANSACTION RECEIPT</div>
  </div>

  <div class="transaction-info">
    <div class="info-section">
      <div class="info-row">
        <span class="info-label">Transaction ID:</span>
        <span class="info-value">#{{ $transaction->tran_id }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Date:</span>
        <span
          class="info-value">{{ $transaction->tran_date ? $transaction->tran_date->format('M d, Y') : 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Time:</span>
        <span class="info-value">{{ $transaction->done_date ? $transaction->done_date->format('H:i:s') : 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Type:</span>
        <span class="info-value">
          @switch($transaction->tran_type)
            @case(1)
              Deposit
            @break

            @case(2)
              Loan Disbursement
            @break

            @case(3)
              Withdrawal
            @break

            @case(4)
              Loan Payment
            @break

            @case(5)
              Interest Payment
            @break

            @case(6)
              Fixed Deposit
            @break

            @case(7)
              Service Fee
            @break

            @case(8)
              Wire Transfer
            @break

            @case(9)
              Currency Exchange
            @break

            @case(10)
              Penalty Fee
            @break

            @case(11)
              FD Maturity
            @break

            @case(12)
              Incoming Wire
            @break

            @default
              Unknown Type
          @endswitch
        </span>
      </div>
    </div>

    <div class="info-section">
      <div class="info-row">
        <span class="info-label">Branch:</span>
        <span class="info-value">{{ $transaction->branch->branch_name ?? 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Currency:</span>
        <span class="info-value">{{ $transaction->currency->currency ?? 'USD' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Processed By:</span>
        <span class="info-value">{{ $transaction->user->first_name ?? 'System' }}
          {{ $transaction->user->last_name ?? '' }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Status:</span>
        <span class="info-value">
          @if ($transaction->approved_by)
            <span class="status-badge status-approved">APPROVED</span>
          @else
            <span class="status-badge status-pending">PENDING</span>
          @endif
        </span>
      </div>
    </div>
  </div>

  <div class="amount-highlight">
    AMOUNT: {{ $transaction->currency->currency ?? 'USD' }} {{ number_format($transaction->amount, 2) }}
  </div>

  @if ($transaction->discription)
    <div style="margin: 20px 0;">
      <strong>Description:</strong>
      <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-left: 4px solid #007bff;">
        {{ $transaction->discription }}
      </div>
    </div>
  @endif

  @if ($transaction->tranDetails && $transaction->tranDetails->count() > 0)
    <div style="margin-top: 30px;">
      <h4 style="border-bottom: 1px solid #333; padding-bottom: 5px;">Transaction Details</h4>
      <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
          <tr style="background: #f8f9fa;">
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">GL Account</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Debit</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Credit</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($transaction->tranDetails as $detail)
            <tr>
              <td style="border: 1px solid #ddd; padding: 8px;">{{ $detail->gl->gl_name ?? $detail->gl_id }}</td>
              <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                {{ $detail->debit_amount ? number_format($detail->debit_amount, 2) : '-' }}
              </td>
              <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                {{ $detail->credit_amount ? number_format($detail->credit_amount, 2) : '-' }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div class="signature-section">
    <div class="signature-box">
      Customer Signature
    </div>
    <div class="signature-box">
      Teller Signature
    </div>
    <div class="signature-box">
      Supervisor Signature
    </div>
  </div>

  <div class="footer">
    <p>This is a computer generated receipt and is valid without signature.</p>
    <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
    <p>Thank you for using our services!</p>
  </div>

  <script>
    // Auto print when page loads
    window.onload = function() {
      window.print();
    }
  </script>
</body>

</html>
