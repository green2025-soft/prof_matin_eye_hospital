# SMS wallet and administration

All wallet, recharge and message records belong to Core and use the **central** connection. TenantCore exposes the current workspace's balance and histories through `/sms`. Hospital and Pharmacy own their SMS feature settings, templates and transaction triggers.

## Models and amounts

| Model / table | Purpose |
| --- | --- |
| `SmsAccount` / `sms_accounts` | One account per `tenant_id`: `enabled`, available `balance`, per-part `rate`, nullable `monthly_limit`, `allowed_features`, nullable encrypted `gateway`. |
| `SmsMessage` / `sms_messages` | Tenant, feature, unique event key, recipient/body, parts, rate/charge snapshots, attempts, status/error and attempt/submission times. Runtime owns reservation, refund and retry transitions. |
| `SmsRecharge` / `sms_recharges` | Tenant, requested amount, payment method/reference, private proof path, pending/approved/rejected status, requester and review audit fields. |

`balance`, `rate`, `charge` and recharge `amount` are integer BDT minor units. Form/display boundaries use existing `Money::minor()` / `moneyDecimal()`. Monthly allowance counts SMS **parts**, not money. Blank monthly limit means unlimited; zero allows no parts. All financial models guard mass assignment.

Private gateway settings use the same HTTP configuration as the global gateway. The whole override is encrypted and hidden. Blank submitted credentials retain their saved values; disabling the private gateway stores `null`, returning the account to the platform gateway. Controllers pass only public connection settings and configured flags to forms, never saved secrets.

## Routes and permissions

| Routes | Permission |
| --- | --- |
| `core.sms-accounts.index` | `sms-accounts.view` |
| `core.sms-accounts.create/store` | `sms-accounts.create` |
| `core.sms-accounts.edit/update` | `sms-accounts.update` |
| `core.sms-recharges.index/show` | `sms-recharges.view` |
| `core.sms-recharges.update` | `sms-recharges.update` |
| `core.sms-history.index/show` | `sms-history.view` |
| `core.sms-history.resolve` | `sms-history.update` |
| `tenantcore.sms.index`, `tenantcore.sms-recharges.index` | `sms.view` |
| `tenantcore.sms-recharges.create/store` | `sms-recharges.create` |

Both proof-download routes authorize `viewProof` against the recharge: a permitted platform reviewer or a wallet viewer in that exact tenant. Recharge-only accounts can download their own proofs. Core administration routes require the central context. Tenant routes require authentication and the current tenant; wallet access remains available for recharge recovery without requiring an active subscription.

The shared `SmsPolicy` uses explicit business abilities: `viewAccounts`, `manageAccounts`, `viewRecharges`, `reviewRecharge`, `viewHistory`, `resolveMessage`, `viewWallet`, `requestRecharge` and `viewProof`. It does not infer permissions from the current route or force financial operations through generic CRUD gates.

## Wallet service

`Modules/Core/app/Services/SmsWalletService.php` owns these operations:

```php
save(Account $actor, ?SmsAccount $record, array $values): SmsAccount
requestRecharge(Account $actor, array $values): SmsRecharge
approve(Account $actor, SmsRecharge $recharge, ?string $note = null): SmsRecharge
reject(Account $actor, SmsRecharge $recharge, ?string $note = null): SmsRecharge
```

`save()` configures account flags, rate, allowance, features and optional private gateway. It never changes balance. A native `Tenant::smsAccount()` relation keeps configured tenants out of the new-account suggestions.

`requestRecharge()` takes the tenant from trusted context, refreshes account authority and stores a pending request. Payment method/reference duplicates are rejected while holding that tenant's account lock. Proof uploads use the existing tenant-scoped private `local` disk under `sms-recharges`; failed persistence removes the newly uploaded file.

Platform review refreshes authority, explicitly transacts on the central connection and locks account before recharge. Approval updates balance and request status together. Repeating an already approved request returns the existing record without crediting it again; repeating rejection also has no financial effect. Approval registers pending SMS processing only after the central commit.

Proof download enters the recorded tenant's storage context and captures its disk in a streamed attachment response. No public proof URL or absolute filesystem path appears in the interface.

## Runtime boundary

`/core/sms-accounts` lists every non-deleting Tenant, including those without an SMS account. Configure selects that Tenant on the existing create page; an existing account opens Edit. The Tenant dropdown shows names.

`SmsAccount::availableFeatures()` returns only the enabled current Tenant's granted feature labels. Its native Tenant relation is read once per request, without a cross-request entitlement cache. No account, a disabled account or an empty grant means no tenant SMS menu, wallet, recharge or module SMS tab, including direct URL access. Hospital and Pharmacy show and accept only granted notification fields; payment grants cover customer collection and supplier payment for that product family. Unposted settings remain saved. Owners cannot bypass the Core allowance. Queue creation skips ungranted features, and delivery rechecks the locked account before charging or sending.

Every notification can use `{organization}`, `{organization_phone}` and `{organization_address}` from the existing central Tenant profile. `SmsService::queue()` snapshots these values into the message without another query. Phone and address are optional; omitted tokens add no text and missing values render empty. Hospital and Pharmacy Settings list these variables beside their templates.

`SmsService` owns transport, message reservation/refunds and the durable outbox. The wallet UI does not change message financial fields. `submitted` records gateway acceptance, not confirmed handset delivery. Uncertain outcomes keep the held charge until a platform operator verifies the provider result and resolves them. The interface initially exposes resolution only for uncertain messages.

Known rejected attempts are refunded and can retry on a later successful action within the runtime's 24-hour window. Uncertain attempts are never automatically resent. Deferred processing runs after normal application responses; this flow requires no cron or queue worker. Central and tenant transactions remain separate; the implementation does not claim cross-database atomicity.
