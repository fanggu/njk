# Price Label Printing Requirements

retrieve the product information from CIN7 by date range, then list the them on the page for view and print.

* select date range by modifiedDate
* print on A4 size paper
* 25 price labels per page
* price label size is 56mm x 36mm
* able to export the price labels as PDF format
* generate barcode image using barcode data

## Product information on the Label:
1. styleCode (SKU)
1. name (Product Name - Chinese)
1. productOptions.option1 (Product Name - English)
1. productOptions.barcode (Product Barcode)
1. productOptions.retailPrice (Product Price - Retail Price)
1. productOptions.specialPrice (Product Price - Special Price)

![njk-price-label.png](njk-price-label.png)

## HTML output structure for price label list
    <html>
    ...

      <body>
        ...

        <!--
          BOF - price label list container
        -->
        <div class='price-label-list'>
          <!-- BOF - price label items-->
          <div class="price-label-item">
            <!-- name (Product Name - Chinese) -->
            <div class="label-name">東北 關廟麵(粗)3kg</div>
            <!-- productOptions.option1 (Product Name - English) -->
            <div class="label-name-english">DB Dry Plain Noodle (Thick) 3Kg</div>
            <!-- productOptions.retailPrice (Product Price - Retail Price) -->
            <div class="label-price ">18.69</div>
            <!-- productOptions.specialPrice (Product Price - Special Price) -->
            <div class="label-price-spcial">16.99</div>
            <!-- productOptions.barcode (Product Barcode) -->
            <div class="label-barcode">
              <svg class="barcode-image"></svg>
            </div>
            <!-- styleCode (SKU) -->
            <div class="label-sku">MNTM002</div>
          </div>
          <!-- ... more price label item -->
          <div class="price-label-item">
            ...
          </div>
          <!-- EOF - price label items -->
        </div>
        <!--
          EOF - price label list container
        -->

      </body>
    </html>

## Product data JSON object example
    {
        "id": 27404,
        "status": "Public",
        "createdDate": "2018-04-11T03:58:52Z",
        "modifiedDate": "2018-06-06T04:32:27Z",
        "styleCode": "IN0465",
        "name": "康師傅 藤椒牛肉麵(袋)97g",
        "description": "<p>KSF Instant Noodle Rattan Pepper Beef 97g</p>",
        "images": [
            {
                "link": "http://cin7.com/webfiles/njkgroupNZ/webpages/images/209329/in0465.jpg"
            }
        ],
        "pdfUpload": null,
        "pdfDescription": null,
        "supplierId": 9082,
        "brand": "",
        "category": "@3.食品Food",
        "subCategory": "速食",
        "categoryIdArray": [
            27
        ],
        "channels": "Magento",
        "weight": 0,
        "height": 0,
        "width": 0,
        "length": 0,
        "volume": 0,
        "stockControl": 5,
        "orderType": "Order",
        "productType": "",
        "projectName": null,
        "optionLabel1": "name_en",
        "optionLabel2": "",
        "optionLabel3": "",
        "customFields": {
            "products_1000": "",
            "products_1001": 0,
            "products_1002": null
        },
        "productOptions": [
            {
                "id": 27322,
                "createdDate": "2018-04-11T03:58:52Z",
                "modifiedDate": "2018-06-15T23:35:34Z",
                "status": "Active",
                "productId": 27404,
                "code": "IN0465",
                "barcode": "6900873097043",
                "supplierCode": "97335",
                "option1": "KSF Instant Noodle Rattan Pepper Beef 97g",
                "option2": "",
                "option3": "6900873097043",
                "optionWeight": 135,
                "size": null,
                "retailPrice": 1.29,
                "wholesalePrice": 1.16,
                "vipPrice": 0,
                "specialPrice": 0,
                "specialsStartDate": null,
                "specialDays": 0,
                "stockAvailable": 12,
                "stockOnHand": 12,
                "image": {
                    "link": "http://cin7.com/webfiles/njkgroupNZ/webpages/images/209329/in0465_tn.jpg"
                },
                "priceColumns": {
                    "retailNZD": 1.29,
                    "generalNZD": 0,
                    "viP10NZD": 1.16,
                    "viP15NZD": 1.1,
                    "viP20NZD": 1.03,
                    "wholesalNZD": 0,
                    "mgtRetail2NZD": 0,
                    "costNZD": 0.7683,
                    "specialPrice": 0
                }
            }
        ]
    }

## CIN7 API Documentation

[Cin7 APIv1 Documentation Link](https://api.cin7.com/API/Help)

[Cin7 Products API Documentation Link](https://api.cin7.com/API/Help/Api/GET-v1-Products_fields_where_order_page_rows)

### PHP HttpRequest Sample

    <?php

    $request = new HttpRequest();
    $request->setUrl('https://api.cin7.com/api/v1/Products');
    $request->setMethod(HTTP_METH_GET);

    $request->setQueryData(array(
      'fields' => 'id,code,styleCode,ModifiedDate,name,ProductOptions',
      'where' => 'modifieddate%3E%272018-06-25T00%3A00%3A00Z%27',
      'order' => 'ASC',
      'page' => '1',
      'rows' => '250'
    ));

    $request->setHeaders(array(
      'Cache-Control' => 'no-cache',
      'Authorization' => 'Basic bmprZ3JvdXBOWjo4N2I1MDczODllNTc0OGZmYjJjZGE1YzBlMmJlZGM1Ng=='
    ));

    try {
      $response = $request->send();

      echo $response->getBody();
    } catch (HttpException $ex) {
      echo $ex;
    }
