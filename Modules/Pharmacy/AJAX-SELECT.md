# AJAX Select: key, value ও label

এই project-এ Select2 dropdown-এর সাধারণ কাজ [resources/js/app.js](../../resources/js/app.js)-এর `window.initAjaxSelects(root)` করে। এটি `select[data-ajax-select]` খুঁজে AJAX search চালু করে; আগে চালু থাকা Select2 আবার তৈরি করে না।

## কোনটি কী কাজ করে

| অংশ | কাজ |
| --- | --- |
| `<select name="customer_id">` | Form submit হলে নির্বাচিত value এই field-এর নামে যায়। |
| `id="pharmacy_customer"` | HTML element চেনায়; label, jQuery বা parent selector এটি ব্যবহার করে। |
| `data-ajax-select` | Shared initializer-কে এই select চালু করতে বলে। |
| `data-url` | Search-এর JSON কোন URL থেকে আসবে। Blade-এর `route()` এই URL তৈরি করে। |
| `data-value-field` | Response row-এর কোন key থেকে option value নেওয়া হবে। Default `id`। |
| `data-label-field` | Response row-এর কোন key dropdown-এ দেখাবে। Default `name`। |
| `data-placeholder` | কিছু নির্বাচন না করলে দেখানো লেখা। Default `Select`। |
| `data-parent-select` | Parent element-এর HTML `id`; এখানে `#` দিতে হয় না। |
| `data-parent-field` | AJAX request-এ parent-এর value কোন query key-তে পাঠাবে। |

`name`, `data-value-field` ও `data-label-field` তিনটি আলাদা বিষয়। যেমন `name="customer_id"` থাকলেও response-এর key `id` হতে পারে। `data-value-field="customer_id"` দিলে response-এ সত্যিই `customer_id` key থাকতে হবে; form field-এর নাম দেখে এটি বদলাতে হয় না।

## Default: ID জমা হবে, Name দেখা যাবে

Category selector-এর সংক্ষিপ্ত উদাহরণ:

```blade
<select name="category_id" data-ajax-select
    data-url="{{ route('pharmacy.categories.options') }}">
    <option value=""></option>
</select>
```

এই response পেলে `Paracetamol` দেখা যাবে, নির্বাচিত value হবে `12`, আর form submit-এ যাবে `category_id=12`:

```json
{
    "results": [
        { "id": 12, "name": "Paracetamol", "type": "medicine" }
    ],
    "pagination": { "more": false }
}
```

`data-value-field="id"` ও `data-label-field="name"` লেখার প্রয়োজন নেই; এগুলো আগেই default। এই catalog endpoint-এর সাধারণ response [Core BaseController::options()](../Core/app/Http/Controllers/BaseController.php) তৈরি করে।

## Customer: ID জমা হবে, সাজানো Text দেখা যাবে

[Sales form](resources/views/sales/form.blade.php)-এ customer-এর label-এ Patient ID, নাম ও mobile একসঙ্গে দেখানো হয়। তাই এখানে শুধু label field বদলানো হয়েছে:

```blade
<select id="pharmacy_customer" name="customer_id" data-ajax-select
    data-url="{{ route('pharmacy.customers.options', ['new_sale' => 1]) }}"
    data-label-field="text" data-placeholder="Patient ID, name or mobile">
    <option value=""></option>
</select>
```

উদাহরণ response:

```json
{
    "results": [
        {
            "id": 7,
            "patient_id": "P000007",
            "name": "Abdul Rahman",
            "mobile_no": "01911065554",
            "type": "customer",
            "text": "P000007 — Abdul Rahman — 01911065554"
        }
    ],
    "pagination": { "more": false }
}
```

Dropdown-এ `text` দেখা যাবে; submit হবে `customer_id=7`। `P000007` প্রদর্শনের Patient ID, database-এর numeric `id` নয়। Response তৈরি হয় [CustomerController::choices()](app/Http/Controllers/CustomerController.php) থেকে [Hospital PatientService](../Hospital/app/Services/PatientService.php)-এর মাধ্যমে।

## Search ও পরের page কীভাবে আসে

Dropdown খুললে বা লিখে search করলে Select2 `data-url`-এ GET request করে। Shared initializer search-এর জন্য 250 ms অপেক্ষা করে এবং এই query values পাঠায়:

```text
search=para&page=1
```

URL-এ আগে থেকে থাকা `new_sale=1`, `product_type=medicine` বা `purpose=sale`-ও request-এ থাকে। নিচে আরও ফল আনলে `page=2` যায়। Server-এর response shape সবসময়:

```json
{
    "results": [],
    "pagination": { "more": false }
}
```

`more=true` মানে আরেক page আছে। কোনো match না থাকলে `results: []`; শুধু একটি plain array বা Laravel paginator-এর পুরো JSON দিলে এই shared adapter সরাসরি কাজ করবে না।

## Response কীভাবে Select2 item হয়

`processResults()` প্রতিটি response row থেকে Select2-এর `id` ও `text` তৈরি করে। বাকি stock, price ও unit তথ্যও রাখে:

```js
results: data.results.map(row => this._normalizeItem({
    ...row,
    id: row[valueField],
    text: row[labelField],
})),
```

এটি [app.js](../../resources/js/app.js)-এর একই mapping, পড়ার সুবিধায় কয়েক লাইনে দেখানো হয়েছে। `valueField` ও `labelField` নতুন database query চালায় না; পাওয়া JSON-এর key থেকে value নেয়।

[Sales Add product picker](resources/views/sales/items.blade.php) `pharmacy.items.options` ব্যবহার করে। [items JSON view](resources/views/selectors/items.blade.php)-এ `id` হলো **ProductVariant-এর ID**; `product_id` হলো Product-এর ID, আর `sku` হলো আলাদা code। Dropdown label `name` থেকে আসে।

এই response-এর `available_quantity`, `sale_price` ও `units` mapping-এর পরও থাকে। [pharmacy-trade-editor.js](../../resources/js/pharmacy-trade-editor.js)-এর `select2:select` handler `event.params.data` থেকে পুরো item নিয়ে editor খোলে; [pharmacy.js](../../resources/js/pharmacy.js) price/unit বসায়। তাই stock বা unit-এর JSON key শুধু dropdown-এর লেখায় দেখা যাচ্ছে না বলে বাদ দেওয়া যাবে না।

Product picker-এ সরাসরি `name` নেই: এটি item বাছাইয়ের control। বাছাইয়ের পর editor-এর `lines[index][variant_id]`-এ variant ID বসে এবং item যোগ করলে সেই line submit হয়। SKU দেখানো বা SKU দিয়ে search করা মানে SKU জমা হওয়া নয়।

## Edit-এর নির্বাচিত option

AJAX result আসার আগেই saved value দেখাতে Blade থেকে একটি selected option দিতে হয়। [Sales form](resources/views/sales/form.blade.php)-এ আছে:

```blade
@if ($selectedCustomer)
    <option value="{{ $selectedCustomer['id'] }}" selected>
        {{ $selectedCustomer['text'] }}
    </option>
@endif
```

এখানেও option value হলো database ID, ভেতরের লেখা হলো label। শুধু `select`-এ value লিখে দিলে remote option নিজে থেকে তৈরি হয় না।

## Parent অনুযায়ী option: বাস্তব উদাহরণ

[Product form](resources/views/products/form.blade.php)-এ hidden `id="type"`-এর value `medicine` বা `optic`। Category select-এ আছে:

```blade
data-parent-select="type" data-parent-field="type"
```

এতে category AJAX request হয় `search=...&page=1&type=medicine`। Parent বদলালে পুরোনো option/value মুছে যায়; parent খালি থাকলে child select disabled থাকে। চলমান পুরোনো request-এর ফলও parent বদলানোর পর বসানো হয় না।

[Sale Return form](resources/views/sale-returns/form.blade.php)-এ parent-এর HTML ID আর request key আলাদা:

```blade
data-parent-select="source_product_type" data-parent-field="product_type"
```

এটি `#source_product_type`-এর value নিয়ে `product_type=medicine` পাঠায়। `name="sale_id"` দিয়ে মূল invoice-এর numeric ID জমা হয়; dropdown-এ invoice number দেখায়। [PharmacySelectorController::saleSources()](app/Http/Controllers/PharmacySelectorController.php) response-এ invoice `number`-কে `name` key হিসেবে দেয়, তাই আলাদা label attribute লাগে না।

## কোন ফাইলে কী পরিবর্তন করবেন

| প্রয়োজন | ফাইল |
| --- | --- |
| সব AJAX select-এর সাধারণ request/mapping | [resources/js/app.js](../../resources/js/app.js) → `initAjaxSelects()` |
| Customer field, label key, edit option | [sales/form.blade.php](resources/views/sales/form.blade.php) |
| Sales product search URL | [sales/items.blade.php](resources/views/sales/items.blade.php) |
| Product/stock/lot/invoice search query | [PharmacySelectorController.php](app/Http/Controllers/PharmacySelectorController.php) |
| Product result-এর JSON keys | [selectors/items.blade.php](resources/views/selectors/items.blade.php) |
| Stock report product labels / lot labels | [selectors/report-products.blade.php](resources/views/selectors/report-products.blade.php), [selectors/lots.blade.php](resources/views/selectors/lots.blade.php) |
| Selector URL ও route name | [routes/web.php](routes/web.php) → `selectors` groups |

শুধু প্রয়োজনীয় attribute রাখুন। Default `id`/`name` আবার লিখবেন না; custom response label হলে `data-label-field="text"` রাখুন। `data-bs-*` Bootstrap modal-এর জন্য, আর `data-datepicker`/`data-input`/`data-toggle` date picker-এর জন্য—এগুলো AJAX select-এর key/value mapping নয়।
