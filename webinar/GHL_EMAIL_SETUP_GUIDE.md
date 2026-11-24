# Payment Confirmation Email Template - GHL Integration Guide

## Email Template: $47 Payment Confirmation

This template is designed for GoHighLevel (GHL) email automations and includes dynamic variables that you can customize.

### File Location
- `payment-confirmation-email-template.html` - Use this for GHL email body

### Dynamic Variables (Replace These in GHL)
Replace these placeholders with your GHL merge tags:

| Variable | Description | GHL Merge Tag Example |
|----------|-------------|----------------------|
| `{{TRANSACTION_ID}}` | Unique transaction ID | `{{transaction_id}}` or `{{contact.transactionId}}` |
| `{{ORDER_DATE}}` | Date of purchase | `{{date}}` or `{{contact.orderDate}}` |
| `{{INVOICE_NUMBER}}` | Invoice number | `{{contact.invoiceNumber}}` |

### GHL Setup Instructions

#### Step 1: Create Automation
1. Go to **GoHighLevel Dashboard**
2. Navigate to **Automations** → **New Automation**
3. Create a trigger (e.g., "Payment Received" or webhook)

#### Step 2: Add Email Step
1. Add an **Email** action to your automation
2. Choose **Custom Email**

#### Step 3: Configure Email
- **From Email:** contact@phelonpreneurnetwork.com
- **Subject Line:** 🎉 Payment Received! Your Confirmation - {{contact.firstName}}
- **Email Body:** Paste the HTML template content
- **Replace merge tags** with your GHL contact field variables

#### Step 4: Dynamic Link
The access link is pre-configured:
```
https://phelonpreneurnetwork.com/webinar/AccessPDF.php?transactionId={{TRANSACTION_ID}}
```

Replace `{{TRANSACTION_ID}}` with your GHL transaction ID merge tag.

### Image Requirements
- **Logo Image URL:** `https://phelonpreneurnetwork.com/images/pp.png`
- **Size:** ~80px height (responsive)
- **Ensure:** Image URL is publicly accessible

### Color Scheme
- **Primary Gold:** #C6A15B
- **Light Gold:** #EAD39A
- **Dark Text:** #111111
- **Light Background:** #f6f0e6
- **Footer Dark:** #111111

### Email Features
✅ Mobile-responsive design  
✅ Professional branding  
✅ Clear pricing display  
✅ Next steps guidance  
✅ Direct access link  
✅ Contact information  
✅ Legal disclaimer  
✅ Social proof footer  

### Testing
1. Send a test email to yourself through GHL
2. Check rendering on desktop, mobile, and tablet
3. Verify all merge tags display correctly
4. Test the access link

### Customization Options

#### Change Product Name
Find and replace:
```html
PHELONPRENEUR Readiness Blueprint
```
With your product name.

#### Adjust Amount
Find and replace:
```html
<div class="price-amount">$47.00</div>
```
With the correct amount.

#### Add Custom Fields
To add more fields in the details section, duplicate this block:
```html
<div class="detail-row">
    <span class="detail-label">Field Name:</span>
    <span class="detail-value">{{MERGE_TAG}}</span>
</div>
```

### Common GHL Merge Tags
- `{{contact.firstName}}`
- `{{contact.lastName}}`
- `{{contact.email}}`
- `{{contact.phone}}`
- `{{date}}`
- `{{time}}`
- `{{workflow.name}}`

### Support
For GHL-specific merge tag syntax, refer to:
- GoHighLevel Documentation
- Your GHL admin panel's merge tag guide
