import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';

class LazyItemLoader extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
        baseColor: Colors.grey[300],
        highlightColor: Colors.grey[100],
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: <Widget>[
            Container(
//            width: 48.0,
              height: 110.0,
              color: Colors.white,
            ),
            SizedBox(
              height: 10,
            ),
            Container(
              alignment: Alignment.centerLeft,
              width: 80,
              height: 20,
              color: Colors.white,
            ),
            SizedBox(
              height: 10,
            ),
            Align(
              alignment: Alignment.centerRight,
              child: Container(
                alignment: Alignment.centerRight,
                width: 50,
                height: 20,
                color: Colors.white,
              ),
            )
          ],
        ));
  }
}
