import 'dart:async';

import 'package:cendme/Customer/provider/product_provider.dart';
import 'package:cendme/Customer/provider/sharedPreferences_provider.dart';
import 'package:cendme/Customer/provider/stores_provider.dart';
import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/Customer/screen/index.dart';
import 'package:cendme/utils/theme_manager.dart';
import 'package:cendme/walkThrough.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/scheduler.dart';
import 'package:flutter/services.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import 'package:page_transition/page_transition.dart';
import 'package:provider/provider.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  // This widget is the root of your application.

  @override
  Widget build(BuildContext context) {
//    SystemChrome.setEnabledSystemUIOverlays(SystemUiOverlay.values);
    SystemChrome.setEnabledSystemUIOverlays([]);

    return MultiProvider(
      providers: [
        ChangeNotifierProvider(
          create: (_) => ThemeManager(),
        ),
        ChangeNotifierProvider(
          create: (_) => UserSharedPreferences(),
        ),
        ChangeNotifierProvider(
          create: (_) => UserProvider(),
        ),
        ChangeNotifierProvider(
          create: (_) => StoreProvider(),
        ),
        ChangeNotifierProvider(
          create: (_) => ProductsProvider(),
        )
      ],
      child: Consumer<ThemeManager>(builder: (context, manager, _) {
        return MaterialApp(
            debugShowCheckedModeBanner: false,
            theme: manager.themeData,
            title: 'CendMe',
            home: Consumer<UserProvider>(
                builder: (context, value, child) {
              value.getUserSharedPreferences();
              return SplashScreen(
                login: value.user?.token == null ? false : true,
              );
            }));
      }),
    );
  }
}

class SplashScreen extends StatefulWidget {
  final bool login;

  const SplashScreen({Key key, this.login}) : super(key: key);

  @override
  _SplashScreenState createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    loadData();
  }

  Future<Timer> loadData() async {
    return new Timer(Duration(seconds: 5), onDoneLoading);
  }

  onDoneLoading() async {
    SchedulerBinding.instance.addPostFrameCallback((_) {
      if (widget.login) {
        Navigator.pushAndRemoveUntil(
            context,
            PageTransition(
                ctx: context,
                alignment: Alignment.center,
                curve: Curves.easeIn,
                duration: Duration(milliseconds: 500),
                type: PageTransitionType.scale,
                child: Index()),(Route<dynamic> route) => false);
      } else {
        Navigator.pushAndRemoveUntil(
            context,
            PageTransition(
                ctx: context,
                alignment: Alignment.center,
                curve: Curves.easeIn,
                duration: Duration(milliseconds: 500),
                type: PageTransitionType.scale,
                child: WalkThrough()),(Route<dynamic> route) => false);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(fit: StackFit.expand, children: [
        Positioned(
          top: 0,
          bottom: 0,
          left: 0,
          right: 0,
          child: Center(
            child: SpinKitDualRing(
              size: 75,
              color: Theme.of(context).accentColor,
            ),
          ),
        ),
        Positioned(
            top: 0,
            bottom: 0,
            left: 0,
            right: 0,
            child: Image.asset(
              "assets/images/cendme_logo.png",
              color: Theme.of(context).accentColor,
              scale: 5.8,
            )),
      ]),
    );
  }
}
