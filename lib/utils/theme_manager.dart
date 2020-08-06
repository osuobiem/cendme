import 'package:cendme/enum/app_theme_enum.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'app_theme.dart';

class ThemeManager with ChangeNotifier {
  ThemeData _themeData;
  final _kThemePreference = "theme_preference";

  /// Use this method on UI to get selected theme.
  ThemeData get themeData {
    if (_themeData == null) {
      _themeData = appThemeData[AppTheme.Light];
    }
    return _themeData;
  }

  ThemeManager() {
    // We load theme at the start
    _loadTheme();
  }

  void _loadTheme() {
    SharedPreferences.getInstance().then((prefs) {
      int preferredTheme = prefs.getInt(_kThemePreference) ?? 0;
      _themeData = appThemeData[AppTheme.values[preferredTheme]];
      // Once theme is loaded - notify listeners to update UI
      notifyListeners();
    });
  }


  /// Sets theme and notifies listeners about change.
  setTheme(AppTheme theme) async {
//    print(theme);
    _themeData = appThemeData[theme];
    // Save selected theme into SharedPreferences
    var prefs = await SharedPreferences.getInstance();
    prefs.setInt(_kThemePreference, AppTheme.values.indexOf(theme));
    notifyListeners();

  }

  getTheme(BuildContext context){
    final Brightness brightnessValue = MediaQuery.platformBrightnessOf(context);
    bool isDark = brightnessValue == Brightness.dark;
  }
}