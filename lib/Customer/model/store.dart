class Store {
  String id;
  String business_name;
  String email;
  String phone;
  String address;
  String balance;
  String photo;

  Store();

  Store.fromJson(Map<String, dynamic> json) {
    id = json['id'].toString();
    business_name = json['business_name'];
    email = json['email'];
    phone = json['phone'];
    address = json['address'];
    balance = json['balance'].toString();
    photo = json['photo'];
  }
}
