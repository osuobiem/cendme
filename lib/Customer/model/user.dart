import 'package:cendme/enum/user_type_enum.dart';

class User {
  String id;
  String name;
  String email;
  String password;
  String phone;
  String gender;
  String address;
  String balance;
  String photo;
  String area_id;
  String area;
  String token;
  bool remember_me;

  User();
//  User(this.id, this.name, this.email, this.phone, this.gender, this.address, this.balance, this.photo, this.area_id, this.token);

  User.fromJson(Map<String, dynamic> json) {
    id = json['user']['id'].toString();
    name = json['user']['name'];
    email = json['user']['email'];
    phone = json['user']['phone'];
    gender = json['user']['gender'];
    address = json['user']['address'];
    balance = json['user']['balance'].toString();
    area_id = json['user']['area_id'];
    area = json['user']['area'] == null ? "": json['user']['area'];
    token = json['token'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = id;
    data['name'] = name;
    data['email'] = email;
    data['password'] = password;
    data['phone'] = phone;
    data['gender'] = gender;
    data['address'] = address;
    data['balance'] = balance;
    data['photo'] = photo;
    data['area_id'] = area_id;
    data['remember_me'] = remember_me;
    data['token'] = token;

    return data;
  }
}
