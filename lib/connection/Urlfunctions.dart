import 'dart:convert';
import 'dart:io';

import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:cendme/utils/constants.dart';

class UrlFunctions {
  static Future getRequest(
      {@required String urlPath, @required String token}) async {
    Dio dio = new Dio();
    Response response;
    String url = Constants.BASE_URL + urlPath;
    Map<String, String> headers = {
      HttpHeaders.acceptHeader: 'application/json',
      HttpHeaders.contentTypeHeader: 'application/x-www-form-urlencoded',
      HttpHeaders.authorizationHeader : 'Bearer $token'
    };
    try {
      response = await dio.get(url, options: Options(headers: headers));

      if (response.statusCode == 200) {
        if(urlPath.contains("/areas/")){
          return {"status": true, "data":response.data};

        }
        if(response.data['success'] == true){
          return {"status": true, "data":response.data};

        }else{
          return {"status": false, "data": response.data};
        }
      }
    } catch (err) {
      return {"status": false, "data": err};
    }
  }

  static Future<Map<String, dynamic>> postRequest(
      {@required String urlPath, @required Map<String, dynamic> data}) async {
    Dio dio = new Dio();

    Response response;
    String url = Constants.BASE_URL + urlPath;
//    print(data);
    Map<String, String> headers = {
      HttpHeaders.acceptHeader: 'application/json',
      HttpHeaders.contentTypeHeader: 'application/x-www-form-urlencoded',
    };
    if (urlPath == "/user/update") {
      String bearer = "Bearer ${data['token']}";
      headers.addAll({HttpHeaders.authorizationHeader: bearer});
    }
    try {
      response =
          await dio.post(url, data: jsonEncode(data), options: Options(headers: headers));

      if (response.statusCode == 200) {
        if(response.data['success'] == true){
          return {"status": true, "data":response.data};

        }else{
          return {"status": false, "data": response.data};
        }
      }
//      return {"status": false, "data": response.data};
    } catch (err) {
//      print(err.message);
//      print(headers);
      return {"status": false, "data": err};
    }
  }
}
