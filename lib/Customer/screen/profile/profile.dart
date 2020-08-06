import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:fluttericon/font_awesome_icons.dart';

class ProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.transparent,
        title: Text("Profile",style: TextStyle(color: Theme.of(context).accentColor, fontSize: 20,letterSpacing: 2),),
        centerTitle: true,
      ),
      body:SingleChildScrollView(
        padding: EdgeInsets.all(20),
        child: Column(
          children: <Widget>[
            Stack(
              children: <Widget>[
                Positioned(
                  bottom: 0,
                  left: 50,
                  child: Icon(FontAwesome.pencil),
                ),
              ],
            ),
            SizedBox(height: 20,),

          ],
        ),
      )
    );
  }
}
