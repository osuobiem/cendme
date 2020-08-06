import 'dart:math';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:cendme/Customer/model/product.dart';
import 'package:cendme/utils/constants.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

class LinearProductItem extends StatelessWidget {
  final Product product;

  LinearProductItem({Key key, @required this.product}) : super(key: key);
  Color lightMood =
  Constants.colorsLight[Random().nextInt(Constants.colorsLight.length)];

  Color darkMood =
  Constants.colorsDark[Random().nextInt(Constants.colorsDark.length)];
  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      child: Card(
        semanticContainer: true,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(
            Radius.circular(10),
          ),
        ),
        elevation: 0.7,
        child: Stack(
          children: <Widget>[
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                Container(
                  padding: EdgeInsets.only(top: 10,left: 10,bottom: 10),
                  height: 110,
//                  width: MediaQuery.of(context).size.width,
                  child: ClipRRect(
                    borderRadius: BorderRadius.all(
                       Radius.circular(10),
                    ),
                    child: CachedNetworkImage(
                      fit: BoxFit.contain,
                      imageUrl:
                      "${product.url}${product.photo}",
                      placeholder: (BuildContext context, String val) {
                        return Container(
                          decoration: BoxDecoration(
                            color: Theme.of(context).brightness ==
                                Brightness.light
                                ? lightMood
                                : darkMood,
                          ),
                          child: Center(
                            child: CupertinoActivityIndicator(),
                          ),
                        );
                      },
                      errorWidget:
                          (BuildContext context, String val, Object e) {
                        return Image.asset(
                          "${product.url}placeholder.png",
                          fit: BoxFit.cover,
                        );
                      },
                    ),
                  ),
                ),
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 10),
                  margin: EdgeInsets.symmetric(vertical: 10),
                  child: Column(
//                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      Container(
                        margin: EdgeInsets.only(bottom: 7),
                        child: Text(
                          product.title?.toUpperCase(),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 15,
                              color: Theme.of(context).accentColor
                          ),
                        ),
                      ),
                      Align(
                        alignment: Alignment.bottomRight,
                        child: Text("₦ ${product.price}",style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(fontSize: 18,fontWeight: FontWeight.w700)),),

                      )
                    ],
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
