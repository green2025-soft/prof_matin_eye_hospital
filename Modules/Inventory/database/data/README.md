# Inventory catalog snapshot

`manifest.json` selects the versioned UTF-8 snapshot and its SHA-256 checksum. New tenant provisioning runs the native root database seeder after migrations and owner creation. Inventory inserts the complete catalog and its completion marker in one tenant transaction. Any completed Inventory seed run prevents later snapshots from changing that tenant's catalog.

The 2026-10-31 snapshot contains 1,225 Medicine and 460 Optic products. It maps `drug_generic` and referenced fallback `generic_names` to categories/generics, `drug_dosage` to dosages, `drug_manufacturer` to suppliers, and `shelf` to shelves. Source keys identify the source table and numeric ID; destination foreign keys use newly allocated IDs.

Every product gets a default SKU and one Piece unit with conversion factor 1. Source pack ratios and structured optical dimensions were unavailable; lens powers remain null. The snapshot retains all products and flags 1400 for missing category/supplier mappings, blank/duplicate product codes, or unavailable default prices. Their `review` entries preserve the reasons. An operator resolves the catalog data before marking those products ready.

Purchase and sale prices are default catalog prices. The 1,222 products without source prices use 0.00 and remain Needs setup until an operator supplies valid prices. This snapshot contains no stock quantities, opening balances, dues, transactions, patients, users, audit actors, credentials, or drug monograph text. Existing and adopted tenants are not seeded by provisioning.
