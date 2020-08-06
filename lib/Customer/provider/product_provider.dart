import 'dart:collection';

import 'package:cendme/Customer/model/product.dart';
import 'package:cendme/Customer/model/user.dart';
import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/connection/Urlfunctions.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class ProductsProvider extends ChangeNotifier {
  User user = new User();

  String _errorMessage;
  String get errorMessage => _errorMessage;

  TextEditingController searchController = new TextEditingController();


  String storeId;
  List _categories = [];
  List<Product> _productToBeShown = [];
  List<Product> _products = [];

  AppState _appState = AppState.initialized;

  AppState get appState => _appState;

  UnmodifiableListView<Product> get getProduct =>
      UnmodifiableListView<Product>(_products);
  UnmodifiableListView<Product> get getProductToBeShown =>
      UnmodifiableListView<Product>(_productToBeShown);

  UnmodifiableListView get categories =>
      UnmodifiableListView(_categories);

  void fetchProduct(context, storeID) async {
    user = Provider.of<UserProvider>(context, listen: false).user;
    if (storeID != storeId) {
      storeId = storeID;
      _productToBeShown.clear();
      notifyListeners();
    }
    if (_products.length > 0) {
      _appState = AppState.completed;
    } else {
      _appState = AppState.loading;
    }
    notifyListeners();

    Map<String, dynamic> response = await UrlFunctions.getRequest(
        urlPath: "/products/all/$storeID", token: user.token);
    if (response["status"] == true) {
      List<Product> tempProducts = [];
      response["data"]['data']['products'].map((each) {
        each['url'] = response["data"]['data']['photo_url'];
        tempProducts.add(Product.fromJson(each));
      }).toList();
//      tempProducts = tempProducts.reversed.toList();
      _productToBeShown = tempProducts;
      _products = tempProducts;
      _appState = AppState.completed;
      if (_categories.length > 0) {
        notifyListeners();
      }
    }

    if (response["status"] == false) {
      if (response["data"] == "Connection failed" ||
          response["data"].toString().contains("Failed host lookup") ||
          response["data"].toString().contains("Connection timed out")) {
        _appState = AppState.connectionError;
        notifyListeners();
      } else {
        _appState = AppState.error;
        notifyListeners();
      }
    }
  }

  void getCategories(context) async {
    user = Provider.of<UserProvider>(context, listen: false).user;
    notifyListeners();
     Map<String, dynamic> response = await UrlFunctions.getRequest(
          urlPath: "/categories", token: user.token);
      if (response["status"] == true) {
        List tempCategory = [];
        tempCategory.addAll(response["data"]['data']['categories']);
        _categories = tempCategory;
        notifyListeners();
    }
  }
}
