<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentLine extends BaseModel
{
    protected $table = 'inventory_document_lines';

    protected array $exposed = ['id', 'document_id', 'variant_id', 'product_unit_id', 'stock_lot_id', 'source_line_id', 'quantity', 'factor', 'base_quantity', 'returned_base_quantity', 'unit_price_minor', 'sale_price_per_piece_minor', 'subtotal_minor', 'discount_minor', 'discount_calculation', 'discount_percent', 'discount_unit_amount_minor', 'invoice_discount_minor', 'vat_mode', 'vat_percent', 'vat_calculation', 'vat_unit_amount_minor', 'vat_minor', 'total_minor', 'cost_minor', 'batch_no', 'expiry_date', 'direction', 'superseded_at', 'created_at'];

    protected function casts(): array
    {
        return ['document_id' => 'integer', 'variant_id' => 'integer', 'product_unit_id' => 'integer', 'stock_lot_id' => 'integer', 'source_line_id' => 'integer', 'quantity' => 'integer', 'factor' => 'integer', 'base_quantity' => 'integer', 'returned_base_quantity' => 'integer', 'unit_price_minor' => 'integer', 'sale_price_per_piece_minor' => 'integer', 'subtotal_minor' => 'integer', 'discount_minor' => 'integer', 'discount_calculation' => 'string', 'discount_percent' => 'decimal:2', 'discount_unit_amount_minor' => 'integer', 'invoice_discount_minor' => 'integer', 'vat_mode' => 'string', 'vat_percent' => 'decimal:2', 'vat_calculation' => 'string', 'vat_unit_amount_minor' => 'integer', 'vat_minor' => 'integer', 'total_minor' => 'integer', 'cost_minor' => 'integer', 'expiry_date' => 'date', 'superseded_at' => 'datetime'];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function sourceLine(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_line_id');
    }

    public function stockLot(): BelongsTo
    {
        return $this->belongsTo(StockLot::class, 'stock_lot_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StockAllocation::class, 'document_line_id');
    }
}
