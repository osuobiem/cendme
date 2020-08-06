import 'dart:math';
import 'package:cendme/utils/constants.dart';
import 'package:cendme/utils/helpers.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';

class ProductCategory extends StatelessWidget {
  final item;
  Color lightMood =
      Constants.colorsLight[Random().nextInt(Constants.colorsLight.length)];

  Color darkMood =
      Constants.colorsDark[Random().nextInt(Constants.colorsDark.length)];

  ProductCategory({Key key, @required this.item}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: <Widget>[
        CircleAvatar(
          child: SvgPicture.asset(
            "assets/images/${Helper.getSvg(item['name'].toString())}",
            semanticsLabel:  item["name"].toString(),
            height: 40,
          ),
          radius: 25,
          backgroundColor: Theme.of(context).brightness == Brightness.light
              ? lightMood.withOpacity(.5)
              : darkMood.withOpacity(.5),
        ),
        Container(
          child: Text(
            item["name"].toString(),
            maxLines: 2,
            style: Theme.of(context).textTheme.bodyText1,
            textAlign: TextAlign.center,
          ),
          width: 70,
        ),
        SizedBox(
          width: 80,
        ),
      ],
    );
  }
}
