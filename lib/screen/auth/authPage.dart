import 'package:cendme/enum/user_type_enum.dart';
import 'package:cendme/screen/auth/signup.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';

import 'login.dart';

class AuthPage extends StatefulWidget {
  final UserType userType;

  const AuthPage({Key key, this.userType}) : super(key: key);
  @override
  _AuthPageState createState() => _AuthPageState();
}

class _AuthPageState extends State<AuthPage> with TickerProviderStateMixin {
  String pageState = "login";
  Widget view = Login();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Stack(
          fit: StackFit.expand,
          children: <Widget>[
            Positioned(
              bottom: 0,
              child: Image.asset('assets/images/image_02.png'),
            ),
            SingleChildScrollView(
              child: Padding(
                  padding: EdgeInsets.only(left: 30.0, right: 30.0, top: 60.0),
                  child: Column(
                    children: <Widget>[
                      Image.asset("assets/images/cendme_logo.png",color: Theme.of(context).accentColor,scale: 3.5,),
                      AnimatedSwitcher(
                        duration: Duration(milliseconds: 300),
                        child: view
                      ),
                      bottomText(),
                      SizedBox(
                        height: 20,
                      ),
                    ],
                  )),
            ),
          ],
        ));
  }

  Widget bottomText() {
    if (pageState == 'login')
      return InkWell(
          onTap: () {
            pageState = "signup";
            view = SignUp();
            setState(() {});
          },
          child: Center(
              child: RichText(
            text: TextSpan(
              text: 'Don\'t have an account?',
              style: Theme.of(context).textTheme.bodyText1,
              children: <TextSpan>[
                TextSpan(
                    text: ' Sign up',
                    style: Theme.of(context).textTheme.headline6,
                    children: [
                      TextSpan(
                          text: ' Now ',
                          style: Theme.of(context).textTheme.bodyText1),
                    ])
              ],
            ),
          )));
    return InkWell(
        onTap: () {
          pageState = "login";
          view = Login();
          setState(() {});
        },
        child: Center(
            child: RichText(
          text: TextSpan(
            text: 'Do you have an account?',
            style: Theme.of(context).textTheme.bodyText1,
            children: <TextSpan>[
              TextSpan(
                text: ' Login',
                style: Theme.of(context).textTheme.headline6,
                  children: [
                    TextSpan(
                        text: ' here ',
                        style: Theme.of(context).textTheme.bodyText1),
                  ]
              )
            ],
          ),
        )));
  }
}
