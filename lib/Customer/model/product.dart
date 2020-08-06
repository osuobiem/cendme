class Product {
  String id;
  String title;
  String details;
  String quantity;
  String price;
  String photo;
  String url;
  String subcategory_id;
  String vendor_id;

  Product();

  Product.fromJson(Map<String, dynamic> json) {
    id = json['id'].toString();
    title = json['title'];
    details = json['details'];
    quantity = json['quantity'].toString();
    price = json['price'].toString();
    photo = json['photo'];
    url = json['url'];
    subcategory_id = json['subcategory_id'];
    vendor_id = json['vendor_id'];
  }
}
