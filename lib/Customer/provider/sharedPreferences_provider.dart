import 'dart:convert';

import 'package:cendme/Customer/model/user.dart';
import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/utils/theme_manager.dart';
import 'package:cendme/walkThrough.dart';
import 'package:flutter/material.dart';
import 'package:page_transition/page_transition.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

class UserSharedPreferences extends ChangeNotifier {
  User _currentUser;
  User get currentUser => _currentUser;

  setUserSharedPreferences(user, [bool rememberMe = true]) async {
    try {
      _currentUser = User.fromJson(user);

      if (rememberMe == true) {
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString("currentUser", jsonEncode(user));
      }
      notifyListeners();
    } catch (e) {
      print("SHARE PRE $e");
    }
  }



  destroyUserSharedPreferences(context) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      await prefs.remove("currentUser");
      _currentUser = new User();
      notifyListeners();
      Navigator.pushAndRemoveUntil(
          context,
          PageTransition(
              ctx: context,
              alignment: Alignment.center,
              curve: Curves.easeIn,
              duration: Duration(milliseconds: 500),
              type: PageTransitionType.scale,
              child: MultiProvider(
                  providers: [
                    ChangeNotifierProvider(
                      create: (_) => ThemeManager(),
                    ),
                    ChangeNotifierProvider(
                      create: (_) => UserSharedPreferences(),
                    ),
                    ChangeNotifierProvider(
                      create: (_) => UserProvider(),
                    )
                  ],
                  child: WalkThrough())),
          (Route<dynamic> route) => false);
    } catch (e) {
      print("SHARE PRE $e");
    }
  }
}
