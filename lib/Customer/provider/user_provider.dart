import 'dart:convert';

import 'package:cendme/Customer/model/user.dart';
import 'package:cendme/Customer/provider/sharedPreferences_provider.dart';
import 'package:cendme/Customer/screen/index.dart';
import 'package:cendme/connection/Urlfunctions.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:cendme/utils/constants.dart';
import 'package:flutter/cupertino.dart';
import 'package:page_transition/page_transition.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

class UserProvider extends ChangeNotifier {
  GlobalKey<FormState> loginFormKey;
  GlobalKey<FormState> signUpFormKey;

  TextEditingController nameController = TextEditingController();
  TextEditingController emailController = TextEditingController();
  TextEditingController phoneController = TextEditingController();
  TextEditingController addressController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  UserProvider() {
    loginFormKey = GlobalKey<FormState>();
    signUpFormKey = GlobalKey<FormState>();
  }

  User _user = new User();

  User get user => _user;

  AppState _appState = AppState.initialized;
  AppState get appState => _appState;

  String _errorMessage;
  String get errorMessage => _errorMessage;

  List _listOfArea = [];
  List get listOfArea => _listOfArea;

  bool checkGenderError = false;
  bool checkStateError = false;

  void login(BuildContext context) async {
    _appState = AppState.loading;
    notifyListeners();
    Map<String, dynamic> response = await UrlFunctions.postRequest(
        data: user.toJson(), urlPath: "/user/login");
    bool remember_me = user.remember_me;
    if (response["status"] == true) {
      _user = User.fromJson(response['data']['data']);
      Provider.of<UserSharedPreferences>(context, listen: false)
          .setUserSharedPreferences(response['data']['data'], remember_me);
      _appState = AppState.completed;
      notifyListeners();
      Navigator.pop(context);
      Navigator.pushAndRemoveUntil(
          context,
          PageTransition(
              ctx: context,
              alignment: Alignment.center,
              curve: Curves.easeIn,
              duration: Duration(milliseconds: 500),
              type: PageTransitionType.scale,
              child: Index()),
          (Route<dynamic> route) => false);
    }
    if (response["status"] == false) {
      Navigator.pop(context);
      if (response["data"] == "Connection failed" ||
          response["data"].toString().contains("Failed host lookup")) {
        _appState = AppState.connectionError;
        notifyListeners();

        Constants.myBar(
            context: context,
            title: "Bad Internet",
            message:
                "Failed to connect to the internet\nPlease check your internet connection and try again");
      } else {
        _errorMessage = response['data']['message'];
        _appState = AppState.error;
        Navigator.pop(context);
        Constants.myBar(
            context: context, title: "Error !!!", message: _errorMessage);
      }
    }

    notifyListeners();
  }

  void signUp(BuildContext context) async {
    _appState = AppState.loading;
    notifyListeners();

    Map<String, dynamic> response = await UrlFunctions.postRequest(
        data: user.toJson(), urlPath: "/user/sign-up");
    if (response["status"] == true) {
      _user = User.fromJson(response['data']['data']);
      Provider.of<UserSharedPreferences>(context, listen: false)
          .setUserSharedPreferences(response['data']['data'], true);
      _appState = AppState.completed;
      notifyListeners();
      Navigator.pop(context);
      Navigator.pushAndRemoveUntil(
          context,
          PageTransition(
              ctx: context,
              alignment: Alignment.center,
              curve: Curves.easeIn,
              duration: Duration(milliseconds: 500),
              type: PageTransitionType.scale,
              child: Index()),
          (Route<dynamic> route) => false);
    }
    if (response["status"] == false) {
      Navigator.pop(context);
      if (response["data"] == "Connection failed" ||
          response["data"].toString().contains("Failed host lookup")) {
        _appState = AppState.connectionError;
        notifyListeners();
        Constants.myBar(
            context: context,
            title: "Bad Internet",
            message:
                "Failed to connect to the internet\nPlease check your internet connection and try again");
      } else {
        _errorMessage = response['data']['message'];
        _appState = AppState.error;
        notifyListeners();
        Constants.myBar(
            context: context, title: "Error !!!", message: _errorMessage);
      }
    }

    notifyListeners();
  }

  getUserSharedPreferences() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      _user = User.fromJson(await jsonDecode(prefs.getString("currentUser")));
      notifyListeners();
    } catch (e) {
      _user = new User();
    }
  }

  changeGender(gender) {
    _user.gender = gender.toString();
    notifyListeners();
  }

  bool checkGenderAndState() {
    if (_user.gender == null) {
      checkGenderError = true;
      notifyListeners();
      return false;
    } else if (_user.area_id == null) {
      checkStateError = true;
      notifyListeners();
      return false;
    } else {
      checkGenderError = false;
      checkStateError = false;
      notifyListeners();
      return true;
    }
  }

  void updateInfo(BuildContext context) async {
    _appState = AppState.loading;
    _user.phone = phoneController.text;
    _user.name = nameController.text;

    Map<String, dynamic> personalMap = new Map();
    _user.toJson().forEach((key, value) {
      if (key != "password" && key != "photo") {
        personalMap[key] = value;
      }
    });
    personalMap['area'] = _user.area_id;

    notifyListeners();
    Map<String, dynamic> response = await UrlFunctions.postRequest(
        data: personalMap, urlPath: "/user/update");
    if (response["status"] == true) {
      response['data']['data']['token'] = _user.token;
      response['data']['data']['user']['area'] = _user.area;
      _user = User.fromJson(response['data']['data']);
      Provider.of<UserSharedPreferences>(context, listen: false)
          .setUserSharedPreferences(response['data']['data'], true);
      _appState = AppState.completed;
      notifyListeners();
      Constants.myBar(
          context: context,
          title: "Successful",
          message:
              "Profile update was successful"); //      Navigator.pop(context);
    }
    if (response["status"] == false) {
//      Navigator.pop(context);
      if (response["data"] == "Connection failed" ||
          response["data"].toString().contains("Failed host lookup")||
          response["data"].toString().contains("Connection timed out")) {
        _appState = AppState.connectionError;
        notifyListeners();
        Constants.myBar(
            context: context,
            title: "Bad Internet",
            message:
                "Failed to connect to the internet\nPlease check your internet connection and try again");
      } else {
        _appState = AppState.error;
        notifyListeners();
        _errorMessage = response['data']['message'];
        notifyListeners();
        Constants.myBar(
            context: context, title: "Error !!!", message: _errorMessage);
      }
    }
  }

  void chooseState(String key) async {
    _listOfArea  = [];
    notifyListeners();
    Map<String, dynamic> response = await UrlFunctions.getRequest(
        urlPath: "/areas/$key", token: _user.token);

    if (response["status"] == true) {

      _listOfArea.addAll(response['data']);
      notifyListeners();
    }

    if (response["status"] == false) {
      if (response["data"] == "Connection failed" ||
          response["data"].toString().contains("Failed host lookup")||
          response["data"].toString().contains("Connection timed out")) {
//        _appState = AppState.connectionError;
//        notifyListeners();
      } else {
//        _errorMessage = response['data']['message'];
//        _appState = AppState.error;
        notifyListeners();
      }
    }
  }

  void chooseArea(Map<dynamic,dynamic> area){
    _user.area_id = area['id'].toString();
    _user.area = area['name'];
    notifyListeners();

  }


  static Future<User> getUser() async {
    User user = new User();
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      user = User.fromJson(await jsonDecode(prefs.getString("currentUser")));
    } catch (e) {
      user = new User();
    }
    return user;
  }
}
