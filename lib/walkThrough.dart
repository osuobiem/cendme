import 'package:cendme/component/walk_carousel.dart';
import 'package:cendme/enum/app_theme_enum.dart';
import 'package:cendme/enum/user_type_enum.dart';
import 'package:cendme/screen/auth/authPage.dart';
import 'package:cendme/utils/theme_manager.dart';
import 'package:cendme/widget/buttonWidget.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:fluttericon/font_awesome5_icons.dart';
import 'package:page_transition/page_transition.dart';
import 'package:provider/provider.dart';

class WalkThrough extends StatelessWidget {
  @override
  Widget build(BuildContext context) {

    return Scaffold(
      body: SingleChildScrollView(
        padding: EdgeInsets.only(top: 100, bottom: 50, right: 30, left: 30),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: <Widget>[
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                Text("Welcome",
                    style: Theme.of(context)
                        .textTheme
                        .headline3
                        .merge(TextStyle(fontSize: 35))),
                IconButton(
                  onPressed: (){
//                    print("hdj");
                    Provider.of<ThemeManager>(context, listen: false).setTheme(
                        Theme.of(context).brightness == Brightness.light
                            ? AppTheme.Dark
                            : AppTheme.Light);
                  },
                  icon:  Icon(Theme.of(context).brightness == Brightness.light
                    ? FontAwesome5.sun
                    : FontAwesome5.moon),color: Theme.of(context).brightness == Brightness.light ? Colors.orangeAccent : Colors.white70,),
              ],
            ),
            SizedBox(
              height: 40,
            ),
            WalkCarousel(),
            SizedBox(
              height: 40,
            ),
            ButtonWidget(
              onPressed: () {
                Navigator.push(context, PageTransition(alignment:  Alignment.center,curve: Curves.easeIn,duration: Duration(milliseconds: 500),type: PageTransitionType.rightToLeft, child: AuthPage(userType: UserType.customer,)));

//                Navigator.of(context).push(MaterialPageRoute(builder: (_)=>AuthPage(userType: UserType.customer,)));
              },
              text:"Shop Now",
            ),
            SizedBox(
              height: 10,
            ),
            ButtonWidget(
              onPressed: () {
                Navigator.push(context, PageTransition(alignment:  Alignment.center,curve: Curves.easeIn,duration: Duration(milliseconds: 500),type: PageTransitionType.rightToLeft, child: AuthPage(userType: UserType.shopper,)));

              },
              text: "Shopper",
            )
          ],
        ),
      ),
    );
  }
}
