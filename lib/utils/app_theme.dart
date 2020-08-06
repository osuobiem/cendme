import 'package:cendme/enum/app_theme_enum.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

import 'constants.dart';

/// Returns enum value name without enum class name.
String enumName(AppTheme anyEnum) {
  return anyEnum.toString().split('.')[1];
}

final appThemeData = {
  AppTheme.Light: ThemeData(
    primaryColor: Colors.white,
    brightness: Brightness.light,
    accentColor: Constants.mainColor(1),
    focusColor: Constants.accentColor(1),
    hintColor: Constants.secondColor(1),
    textTheme: GoogleFonts.nunitoTextTheme(TextTheme(
      headline5: TextStyle(fontSize: 20.0, color: Constants.secondColor(1)),
      headline4: TextStyle(
          fontSize: 18.0,
          fontWeight: FontWeight.w600,
          color: Constants.secondColor(1)),
      headline3: TextStyle(
          fontSize: 20.0,
          fontWeight: FontWeight.w600,
          color: Constants.secondColor(1)),
      headline2: TextStyle(
          fontSize: 22.0,
          fontWeight: FontWeight.w700,
          color: Constants.mainColor(1)),
      headline1: TextStyle(
          fontSize: 22.0,
          fontWeight: FontWeight.w300,
          color: Constants.secondColor(1)),
      subtitle1: TextStyle(
          fontSize: 15.0,
          fontWeight: FontWeight.w500,
          color: Constants.secondColor(1)),
      headline6: TextStyle(
          fontSize: 16.0,
          fontWeight: FontWeight.w600,
          color: Constants.mainColor(1)),
      bodyText2: TextStyle(fontSize: 12.0, color: Constants.secondColor(1)),
      bodyText1: TextStyle(fontSize: 14.0, color: Constants.secondColor(1)),
      caption: TextStyle(fontSize: 12.0, color: Constants.accentColor(1)),
    )),
  ),
  AppTheme.Dark: ThemeData(
    primaryColor: Colors.black,
    brightness: Brightness.dark,
    scaffoldBackgroundColor: Colors.black,
    accentColor: Constants.mainDarkColor(1),
    hintColor: Constants.secondDarkColor(1),
    focusColor: Constants.accentDarkColor(1),
    textTheme: GoogleFonts.nunitoTextTheme(TextTheme(
      headline5: TextStyle(fontSize: 20.0, color: Constants.secondDarkColor(1)),
      headline4: TextStyle(
          fontSize: 18.0,
          fontWeight: FontWeight.w600,
          color: Constants.secondDarkColor(1)),
      headline3: TextStyle(
          fontSize: 20.0,
          fontWeight: FontWeight.w600,
          color: Constants.secondDarkColor(1)),
      headline2: TextStyle(
          fontSize: 22.0,
          fontWeight: FontWeight.w700,
          color: Constants.mainDarkColor(1)),
      headline1: TextStyle(
          fontSize: 22.0,
          fontWeight: FontWeight.w300,
          color: Constants.secondDarkColor(1)),
      subtitle1: TextStyle(
          fontSize: 15.0,
          fontWeight: FontWeight.w500,
          color: Constants.secondDarkColor(1)),
      headline6: TextStyle(
          fontSize: 16.0,
          fontWeight: FontWeight.w600,
          color: Constants.mainDarkColor(1)),
      bodyText2: TextStyle(fontSize: 12.0, color: Constants.secondDarkColor(1)),
      bodyText1: TextStyle(fontSize: 14.0, color: Constants.secondDarkColor(1)),
      caption: TextStyle(fontSize: 12.0, color: Constants.secondDarkColor(0.6)),
    )),
  ),
};
