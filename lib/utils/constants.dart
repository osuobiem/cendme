import 'package:flushbar/flushbar.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';

class Constants {
  static const String BASE_URL = "https://cendme.com/api";
  static const Username = "edim1925.de@gmail.com";
  static const Password = "Samuel1925.";

  //  Color _mainColor = Color(0xFFFF4E6A);
  static Color _mainColor = Color(0xFFee5b2d);
  static Color _mainDarkColor = Color(0xFFee5b2d);
  static Color _secondColor = Color(0xFF344968);
  static Color _secondDarkColor = Color(0xFFccccdd);
  static Color _accentColor = Color(0xFF8C98A8);
  static Color _accentDarkColor = Color(0xFF9999aa);
  static List<Color> colorsLight = [
    Color(0xffffaaa5),
    Color(0xffeab0d9),
    Color(0xff88e1f2),
    Color(0xffc0ffb3)
  ];
  static List<Color> colorsDark = [
    Color(0xff1b262c),
    Color(0xff0f4c75),
    Color(0xff810000),
    Color(0xff698474)
  ];

  static Color mainColor(double opacity) {
    return _mainColor.withOpacity(opacity);
  }

  static Color secondColor(double opacity) {
    return _secondColor.withOpacity(opacity);
  }

  static Color accentColor(double opacity) {
    return _accentColor.withOpacity(opacity);
  }

  static Color mainDarkColor(double opacity) {
    return _mainDarkColor.withOpacity(opacity);
  }

  static Color secondDarkColor(double opacity) {
    return _secondDarkColor.withOpacity(opacity);
  }

  static Color accentDarkColor(double opacity) {
    return _accentDarkColor.withOpacity(opacity);
  }

  static Flushbar myBar({context, title, message}) {
    return Flushbar(
      backgroundColor: Theme.of(context).accentColor,
      margin: EdgeInsets.all(20),
      flushbarPosition: FlushbarPosition.TOP,
      flushbarStyle: FlushbarStyle.FLOATING,
      borderRadius: 20,
      dismissDirection: FlushbarDismissDirection.HORIZONTAL,
      padding: EdgeInsets.all(10),
      title: title,
      message: message,
      duration: Duration(seconds: 3),
    )..show(context);
  }
}
