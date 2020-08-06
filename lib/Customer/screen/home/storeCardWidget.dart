import 'dart:math';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:cendme/Customer/model/store.dart';
import 'package:cendme/Customer/screen/product/list_products_in_store.dart';
import 'package:cendme/utils/constants.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';

class StoreCardWidget extends StatelessWidget {
  final Store store;
  StoreCardWidget({@required this.store});
  Color lightMood =
  Constants.colorsLight[Random().nextInt(Constants.colorsLight.length)];

  Color darkMood =
  Constants.colorsDark[Random().nextInt(Constants.colorsDark.length)];

  @override
  Widget build(BuildContext context) {

    return GestureDetector(
      onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ListProducts(title: store.business_name,storeId: store.id,),)),
      child: Card(
        semanticContainer: true,
        color: Theme.of(context).brightness == Brightness.light
            ? lightMood
            : darkMood,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(
            Radius.circular(10),
          ),
        ),
        elevation: 0.7,
        child: Stack(
          children: <Widget>[
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                Container(
                  height: 110,
//                  width: MediaQuery.of(context).size.width,
                margin: EdgeInsets.only(top: 10),
                alignment: Alignment.center,
                  child: ClipRRect(
                    borderRadius: BorderRadius.all(
                       Radius.circular(10),
                    ),
                    child: CachedNetworkImage(
                      fit: BoxFit.contain,
                      imageUrl:
                          "https://cendme.com/storage/vendors/${store.photo}",
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
                          "https://cendme.com/storage/vendors/placeholder.png",
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
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      Container(
                        margin: EdgeInsets.only(bottom: 7),
                        child: Hero(
                          tag: store.id,
                          child: Text(
                            store.business_name?.toUpperCase(),
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              fontWeight: FontWeight.w800,
                              fontSize: 15,
                            ),
                          ),
                        ),
                      ),
                      Container(
                        margin: EdgeInsets.only(bottom: 3),
                        child: Text(
                          store.address?.toUpperCase(),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                          style: Theme.of(context).textTheme.caption,
                        ),
                      ),
                      Text(
                        store.email,
                        style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 11,
                            letterSpacing: 1.0),
                      ),
                      Wrap(
                        children: <Widget>[
                          Text(
                            store.phone,
                            style: TextStyle(
                                fontWeight: FontWeight.w600,
                                fontSize: 11,
                                letterSpacing: 1.0),
                          ),
                        ],
                      ),
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
