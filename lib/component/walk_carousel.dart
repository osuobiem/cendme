import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'package:fluttericon/font_awesome5_icons.dart';

class WalkCarousel extends StatefulWidget {
  @override
  _WalkCarouselState createState() => _WalkCarouselState();
}

class _WalkCarouselState extends State<WalkCarousel> {
  final List<Slider> sliderItems = [
    new Slider(FontAwesome5.stopwatch, "Your shopping in 1 hour",
        "Do not ever wait all afternoon to reach the purchase. We'll deliver it to you whenever you want in one-hour intervals."),
    new Slider(FontAwesome5.user_circle, "Personalized service",
        "You will have direct contact with the Shopper. A Shopper will prepare your shopping as if it were theirs, always looking for the best for you."),
    new Slider(FontAwesome5.user_check, "Trusted products",
        "Great variety of supermarkets to choose from. Choose from one of our online supermarkets to do your shopping."),
    new Slider(FontAwesome5.truck_loading, "Fresh products",
        "Buying fresh products online is no longer a problem. Our shoppers will buy your products to order respecting the cold chain."),
  ];

  int _current = 0;

  @override
  Widget build(BuildContext context) {
    return Container(
//      height: MediaQuery.of(context).size.height * .5,
      child: Column(
        children: <Widget>[
          CarouselSlider(
            options: CarouselOptions(
                autoPlay: true,
                autoPlayCurve: Curves.easeInOut,
                height: MediaQuery.of(context).size.height * .4,
                onPageChanged: (index, reason) {
                  setState(() {
                    _current = index;
                  });
                }),
            items: sliderItems
                .map(
                  (slide) => Column(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: <Widget>[
                      Icon(
                        slide.icon,
                        size: 50,
                        color: Theme.of(context).accentColor,
                      ),
                      SizedBox(
                        height: 10,
                      ),
                      Text(
                        slide.title,
                        style: Theme.of(context)
                            .textTheme
                            .headline6
                            .merge(TextStyle(fontSize: 20)),
                        textAlign: TextAlign.center,
                      ),
                      SizedBox(
                        height: 10,
                      ),
                      Text(
                        slide.message,
                        textAlign: TextAlign.center,
                        style: Theme.of(context).textTheme.caption.merge(
                            TextStyle(
                                fontSize: 15, fontWeight: FontWeight.w500)),
                      ),
                    ],
                  ),
                )
                .toList(),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: sliderItems.map((url) {
              int index = sliderItems.indexOf(url);
              return Container(
                width: 8.0,
                height: 8.0,
                margin: EdgeInsets.symmetric(vertical: 10.0, horizontal: 2.0),
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: _current == index
                      ? Color.fromRGBO(0, 0, 0, 0.9)
                      : Color.fromRGBO(0, 0, 0, 0.4),
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );
  }
}

class Slider {
  final IconData icon;
  final String title;
  final String message;

  Slider(this.icon, this.title, this.message);
}
