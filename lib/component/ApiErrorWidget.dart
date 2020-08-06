import 'package:cendme/widget/buttonWidget.dart';
import 'package:flutter/material.dart';

class ApiErrorWidget extends StatelessWidget {
  final VoidCallback onPressed;
  final String text;

  const ApiErrorWidget({Key key, @required this.onPressed, @required this.text}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
        height: 150,
        padding: EdgeInsets.all(10),
        decoration: BoxDecoration(
          borderRadius: BorderRadius.all(Radius.circular(10)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Text(
              text,
              style: TextStyle(
                  letterSpacing: 1,
                  fontSize: 15,
                  fontWeight: FontWeight.bold,
                  color: Theme.of(context).accentColor),
            ),
            SizedBox(
              height: 15,
            ),
            ButtonWidget(
              onPressed: onPressed,
              text: "Retry",
            )
          ],
        )
    );
  }
}
