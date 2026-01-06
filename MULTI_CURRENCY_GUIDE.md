# Multi-Currency & English Translation - Quick Reference Guide

## What's New

### 🌍 Full English Translation
All user-facing text has been translated from Indonesian to English:
- **Home Page:** "Exclusive MaxuMax Jerseys" with all sections in English
- **Pre-order Landing:** "Select Your Jersey" with English product descriptions
- **Pre-order Form (4-Step Stepper):** All steps, labels, and buttons in English
- **Thank You Page:** Confirmation message and order summary in English
- **Admin Panel:** Price labels updated for clarity

### 💱 Multi-Currency Support
Users can now select their preferred currency when pre-ordering:

**Available Currencies:**
- **RM (Malaysian Ringgit)** - Default
- **$ (Brunei Dollar)**
- **Rp (Indonesian Rupiah)**

**How It Works:**
1. Select currency from dropdown at top-right of pre-order form
2. All prices update in real-time:
   - Base price converts based on exchange rate
   - Add-on prices (Long Sleeve, Nameset) adjust per currency
   - Total price recalculates automatically

**Example Price Conversions:**
- MYR (Base): RM 49.99 + RM 3 (Long Sleeve) = RM 52.99
- BND: $ 52.49 + $ 3.15 = $ 55.64
- IDR: Rp 259,700 + Rp 15,600 = Rp 275,300

**Currency Symbols:**
- RM or $ or Rp displays next to all prices
- IDR amounts formatted with thousands separator (e.g., "Rp 275,300")
- MYR and BND use 2 decimal places

### 📋 Form Features

**Currency Persistence:**
- Selected currency is saved with each pre-order
- Receipt and admin panel show the currency used
- Users can see exactly what currency they paid in

**Dynamic Price Updates:**
- Change currency → prices update instantly
- Change quantity → total recalculates in current currency
- Add Long Sleeve → price updates per currency
- Add Nameset → price updates per currency

**All 4-Step Form in English:**
1. **Product Confirmation** - Verify product and initial price
2. **Your Details** - Enter name, email, phone, address (all fields labeled in English)
3. **Jersey Customization** - Size, Long Sleeve, Nameset, quantity, special requests
4. **Review** - Final summary with selected currency and total price

---

## Technical Implementation

### Frontend
- Currency selector in `preorder/create.blade.php`
- JavaScript handles real-time conversion
- Rates defined in currency configuration object

### Backend
- PreorderController stores selected currency with order
- Applies currency-specific pricing during checkout
- Calculates total amount in selected currency

### Database
- Preorder table stores currency field
- Currency displayed in admin panel and thank you page

---

## Currency Exchange Rates

| Currency | Rate | Base Add-on | Nameset Add-on |
|----------|------|------------|----------------|
| MYR (RM) | 1.00 | RM 3      | RM 13         |
| BND ($)  | 1.05 | $ 3.15    | $ 13.65       |
| IDR (Rp) | 5200 | Rp 15,600 | Rp 67,600     |

*Note: Rates are approximate and can be adjusted by updating the currency configuration in the form JavaScript*

---

## User Experience Flow

### For Customer
1. Visit home page → Click "Start Pre-order Now"
2. Select jersey from pre-order landing page
3. Fill 4-step form:
   - **Step 1:** Confirm product (see price in default currency)
   - **Step 2:** Enter personal details
   - **Step 3:** Customize jersey and select currency ← **CURRENCY SELECTION HERE**
   - **Step 4:** Review order with all prices in selected currency
4. Submit pre-order
5. See confirmation with selected currency and total

### For Admin
1. View pre-orders in admin panel
2. See each order's selected currency next to total amount
3. Example: "MYR 99.99" or "IDR 519,400"
4. Export CSV includes currency field

---

## File Changes Summary

| File | Change | Type |
|------|--------|------|
| `home.blade.php` | Translated entire landing page | Translation |
| `preorder/landing.blade.php` | Translated product selection page | Translation |
| `preorder/create.blade.php` | Translated form + added currency selector + multi-currency JS | Translation + Feature |
| `preorder/thankyou.blade.php` | Translated thank you page | Translation |
| `PreorderController.php` | Added currency-aware pricing logic | Backend |
| `admin/products/edit.blade.php` | Updated price label clarity | UI |
| Migrations | Updated comments and defaults to MYR | Comments |

---

## Testing the Feature

### ✅ To Test Currency Selection
1. Go to pre-order form
2. Look at top-right → see currency dropdown (default: RM)
3. Click dropdown → see IDR and BND options
4. Select IDR → watch all prices update to Rp
5. Check format: "Rp 259,700" (thousands separator)
6. Select MYR → back to "RM 49.99" format

### ✅ To Test Real-time Updates
1. Select currency
2. Increase quantity → total updates in selected currency
3. Check Long Sleeve box → price increases appropriately for currency
4. Check Nameset box → price increases appropriately for currency
5. Change currency → all prices recalculate

### ✅ To Test Order Confirmation
1. Complete pre-order with specific currency
2. Check thank you page → currency should match selection
3. Check admin panel → currency should be displayed with amount

---

## Future Enhancement Ideas

- Add currency preference to user profile
- Implement real-time exchange rate fetching
- Add currency-specific payment methods
- Support more currencies as demand grows
- Admin panel currency conversion tools

---

**Status:** ✅ All translations complete | ✅ Multi-currency working | ✅ Form field storage configured | ✅ Admin display ready
