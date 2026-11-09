# Pharmacy catalog snapshots

`manifest.json` identifies the bundled UTF-8 snapshots and their SHA-256 checksums. Fresh tenant provisioning calls the native root database seeder after migrations and owner creation. It initializes 460 Optic products, then exactly 325 Medicine products. The source SQL files and legacy database are not required on the receiving server.

## Optic base: 2026-11-07-v2

This snapshot retains the original 460 Optic products and parent catalogs for categories, dosages, suppliers, shelves and units. It contains no Medicine products. Source keys identify source records; destination foreign keys use local IDs. Existing tenants keep their Optic catalog unchanged.

Every product receives a default variant and a Piece unit with factor 1. Missing source prices remain zero; missing optical dimensions remain null. The historical `review` and `needs_setup` metadata record unavailable information.

## Medicine replacement: medicine-2026-11-07-v2

The replacement contains exactly 325 `Medicine` records from `inv_product (2).sql`, their `inv_product_basic.sql` details, and 94 referenced category names from the legacy `prof_matin_eye_hospital.inv_category` table. Source file hashes and lookup provenance are included in the snapshot. Optics, OT and orphan basic rows are excluded.

The tenant-wide catalog uses branch 1's basic information, selecting the latest effective timestamp and then highest ID for duplicate rows. `sales_price` becomes Sale Price; `reminder_level` becomes Re-order Level. All source units are Pcs. No purchase price, supplier, dosage or shelf is inferred. `Virux 200 mg tab` (source ID 506, code M-0298) has no basic row, so its Sale Price and Re-order Level start at zero.

The one-time replacement keeps matching IDs and generated codes, writes the source names and basic information, and assigns canonical `inv_product:<id>` source keys to all 325 medicines. `existing_source_key` identifies the 259 safe aliases from the historical catalog; it is only used to adopt those existing identities. Other old Medicine products and their own units/variants are removed. Restrictive foreign keys prevent silently deleting referenced transaction or prescription data; any necessary history cleanup requires separate explicit authorization.

New records receive native generated product codes, default variants and Pcs units. A source code becomes the optional barcode when no other product or variant owns it. Existing transaction quantities are not imported from these snapshots.

The replacement locks the tenant's product identifier counter and commits catalog changes with its own `phr_seed_runs` version marker. Repeating a completed version does nothing, preserving subsequent operator edits and additions. Existing tenants require an explicit seeding run in their native tenant context; adoption and `tenants:migrate` do not seed catalogs. The earlier base and Medicine v1 snapshots are retained only as immutable history and are not selected by the manifest.

Snapshots contain no stock, opening balances, dues, transactions, patients, users or credentials.
