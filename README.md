# Add product labels from amasty to product type response
Module for Magento 2

**StorefrontX_ProductLabelsExtension** Add product labels from amasty to product type response

Added functionality to remove label "Discount" when field is empty.


## Dependencies

### Magento module GraphQl

**Composer module name: magento/module-graph-ql**

### Amasty Product Labels GraphQL

Product Labels GraphQL extension by Amasty.
**Composer module name: amasty/product-labels-graphql**


### EXAMPLE:

Request

````graphql
{
products(search:"28"){
    items {
        name
        product_labels{
            items {
                label_id
                image
                name
                size
                txt
            }
        }
    }
}
}

````

Response 
````graphql 
{
  "data": {
    "products": {
      "items": [
        {
          "name": "Test Product - 28",
          "product_labels": {
            "items": [
              {
                "label_id": 1,
                "image": "media/amasty/amlabel/new-arrival.png",
                "name": "New Label",
                "size": "",
                "txt": ""
              }
            ]
          }
        }
      ]
    }
  }
}


````

## License

The module is licensed under the MIT license.