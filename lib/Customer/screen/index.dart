import 'package:cendme/Customer/provider/sharedPreferences_provider.dart';
import 'package:cendme/Customer/screen/Settings/settings.dart';
import 'package:cendme/enum/app_theme_enum.dart';
import 'package:cendme/utils/theme_manager.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:fluttericon/entypo_icons.dart';
import 'package:fluttericon/font_awesome5_icons.dart';
import 'package:persistent_bottom_nav_bar/persistent-tab-view.dart';
import 'package:provider/provider.dart';

import 'home/home.dart';

class Index extends StatefulWidget {
  Index({Key key}) : super(key: key);

  @override
  _IndexState createState() => _IndexState();
}

class _IndexState extends State<Index> {
  PersistentTabController _controller;

  @override
  void initState() {
    _controller = PersistentTabController(initialIndex: 0);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
        onWillPop: () async => false,
        child: PersistentTabView(
          controller: _controller,
          screens: _buildScreens(),
          items: _navBarsItems(),
          confineInSafeArea: true,
          backgroundColor: Theme.of(context).brightness == Brightness.light
              ? Colors.white
              : Colors.black,
          handleAndroidBackButtonPress: true,
          resizeToAvoidBottomInset:
              true, // This needs to be true if you want to move up the screen when keyboard appears.
          stateManagement: false,
          hideNavigationBarWhenKeyboardShows:
              false, // Recommended to set 'resizeToAvoidBottomInset' as true while using this argument.
          popAllScreensOnTapOfSelectedTab: true,
          itemAnimationProperties: ItemAnimationProperties(
            // Navigation Bar's items animation properties.
            duration: Duration(milliseconds: 300),
            curve: Curves.ease,
          ),
          screenTransitionAnimation: ScreenTransitionAnimation(
            // Screen transition animation on change of selected tab.
            animateTabTransition: true,
            curve: Curves.easeIn,
            duration: Duration(milliseconds: 300),
          ),
          navBarStyle: NavBarStyle
              .style10, // Choose the nav bar style with this property.
        ));
  }

  List<Widget> _buildScreens() {
    return [
      HomePage(),
      Scaffold(
        body: Container(
          child: Center(
              child: IconButton(
                onPressed: () {
                  Provider.of<ThemeManager>(context, listen: false).setTheme(
                      Theme.of(context).brightness == Brightness.light
                          ? AppTheme.Dark
                          : AppTheme.Light);
                  Provider.of<UserSharedPreferences>(context, listen: false).destroyUserSharedPreferences(context);
                },
                icon: Icon(Theme.of(context).brightness == Brightness.light
                    ? FontAwesome5.sun
                    : FontAwesome5.moon),
                color: Theme.of(context).brightness == Brightness.light
                    ? Colors.orangeAccent
                    : Colors.white70,
              )),
        ),
      ),
      Settings()
    ];
  }

  List<PersistentBottomNavBarItem> _navBarsItems() {
    return [
      PersistentBottomNavBarItem(
          icon: Icon(Entypo.home),
          title: ("Home"),
          activeColor: Theme.of(context).accentColor.withOpacity(.9),
          inactiveColor: Theme.of(context).accentColor.withOpacity(.7),
          titleFontSize: 14,
          activeContentColor: Theme.of(context).scaffoldBackgroundColor),
      PersistentBottomNavBarItem(
          icon: Icon(FontAwesome5.expand),
          title: ("Settings"),
          titleFontSize: 14,
          activeColor: Theme.of(context).accentColor.withOpacity(.9),
          inactiveColor: Theme.of(context).accentColor.withOpacity(.7),
          activeContentColor: Theme.of(context).scaffoldBackgroundColor),
      PersistentBottomNavBarItem(
          icon: Icon(FontAwesome5.user_cog),
          title: ("Settings"),
          titleFontSize: 14,
          activeColor: Theme.of(context).accentColor.withOpacity(.9),
          inactiveColor: Theme.of(context).accentColor.withOpacity(.7),
          activeContentColor: Theme.of(context).scaffoldBackgroundColor),
    ];
  }
}
