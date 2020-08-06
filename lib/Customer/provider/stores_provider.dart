import 'dart:collection';

import 'package:cendme/Customer/model/store.dart';
import 'package:cendme/Customer/model/user.dart';
import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/connection/Urlfunctions.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';


class StoreProvider extends ChangeNotifier {
  User user = new User();

  String _errorMessage;
  String get errorMessage => _errorMessage;

  List<Store> storesToBeShown = [];
  List<Store> stores = [];

  AppState _appState = AppState.initialized;

  AppState get appState => _appState;

  UnmodifiableListView<Store> get getStores => UnmodifiableListView<Store>(stores);
  UnmodifiableListView<Store> get getStoresToBeShown => UnmodifiableListView<Store>(storesToBeShown);

  Store getSingleStore(String id) =>
      stores.firstWhere((each) => each.id == id, orElse: () => Store());


  void clearSearchResult() {
    storesToBeShown = stores;
    notifyListeners();
  }

  void searchStoreList(String val) {
    storesToBeShown = stores
        .where((each) =>
    each.business_name.toLowerCase().contains(val.toLowerCase()) ||
        each.address.toLowerCase().contains(val.toLowerCase()))
        .toList();
    notifyListeners();
  }


  void fetchStores(context) async {
    user = Provider.of<UserProvider>(context,listen: false).user;

    if (stores.length > 0) {
      _appState = AppState.completed;
    } else {
      _appState = AppState.loading;
    }
    notifyListeners();

    Map<String, dynamic> response = await UrlFunctions.getRequest(urlPath: "/vendors/${user.area_id}", token: user.token);
      if (response["status"] == true) {
        List<Store> tempStores = [];
        response["data"]['data']['vendors'].map((each) {
          tempStores.add(Store.fromJson(each));
        }).toList();
        storesToBeShown = tempStores;
        stores = tempStores;
        _appState = AppState.completed;
        notifyListeners();
      }

    if (response["status"] == false) {
//      Navigator.pop(context);
      if (response["data"] == "Connection failed" ||
          response["data"].toString().contains("Failed host lookup")||
          response["data"].toString().contains("Connection timed out")) {
        _appState = AppState.connectionError;
        notifyListeners();
      } else {
//        _errorMessage = response['data']['message'];
        _appState = AppState.error;
        notifyListeners();
      }
    }
  }
}
