# Price Label Printing Requirements

retrieve the product information from CIN7 by date range, then list the them on the page for view and print.

* print on A4 size paper
* 25 price label per page
* price label size is 56mm x 36mm

## Product information on the Label:
1. styleCode
1. name
1. productOptions.option1
1. productOptions.barcode
1. productOptions.retailPrice
1. productOptions.specialPrice

![njk-price-label.png](njk-price-label.png)

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
