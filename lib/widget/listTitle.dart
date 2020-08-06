import 'package:flutter/material.dart';

class ListTitle extends StatelessWidget {
  final String header;
  final String title;

  const ListTitle({Key key, @required this.header, @required this.title}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.start,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        Text(
          header,
          style:
          TextStyle(color: Theme.of(context).hintColor, fontSize: 13.0),
        ),
        SizedBox(
          height: 9.0,
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: <Widget>[
            Text(
              title == null ? "": title,
              style: TextStyle(
                  fontSize: 17.0,
                  letterSpacing: 1.5,
                  fontWeight: FontWeight.w300),
            ),
            Icon(Icons.keyboard_arrow_right,color: Theme.of(context).accentColor,)
          ],
        ),
        line(context)
      ],
    );
  }
}


Widget line(context) {
  return Padding(
    padding: const EdgeInsets.only(top: 10.0),
    child: Container(
      width: double.infinity,
      height: 0.5,
      decoration: BoxDecoration(color: Theme.of(context).hintColor),
    ),
  );
}