import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';

class ButtonWidget extends StatelessWidget {
  final String text;
  final VoidCallback onPressed;

  const ButtonWidget(
      {Key key,
      @required this.onPressed,
      @required this.text})
      : super(key: key);
  @override
  Widget build(BuildContext context) {
    return FlatButton(
      onPressed: onPressed,
       color: Theme.of(context).accentColor,
       padding: EdgeInsets.all(10),
       child: Text(text,
            style: TextStyle(
                fontSize: 17.0,
                letterSpacing: 1.5,
                fontWeight: FontWeight.w600),)
    );
  }
}
