# Prescription Dose CRUD

`PrescriptionDose` is a tenant-wide catalog of dose instructions used by the General Prescription medicine editor.
It follows the normal Blade CRUD flow: route, `PrescriptionDoseRequest`, `PrescriptionDoseController`,
the Prescription module `BaseModel`, and an index plus shared create/edit form.

## Routes and access

The resource is available at `/prescription/prescription-dose` and uses the named routes
`prescription.prescription-dose.*`. It is authenticated, tenant-scoped, subscription-protected, and
authorized by the `prescription-dose.view/create/update/delete` role and plan permissions. Dose
management is tenant-wide and does not require a selected branch.

## Data flow

The `prescription_doses` tenant table stores a dose name. The request validates the name and shared
CRUD authorization/persistence handles create, update, and delete. The prescription form controller
loads dose options in ID order; the medicine modal renders those names in the existing dose dropdown.
Selecting a preset copies its name into the medicine's `frequency` field. Custom dose text remains
editable and is saved through the existing Prescription aggregate service.

The modal keeps its existing newest-first medicine order. The prescription pad preview uses the
saved medicine ordering in reverse so the first medicine added remains first in the preview, including
after a validation retry.

Deploy the new tenant migration and the permission-catalog update through the existing
`php artisan tenants:migrate` workflow; do not seed example dose records.
