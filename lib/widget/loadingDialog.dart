import 'package:flutter/material.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';

class LoadingDialog extends StatelessWidget {

  @override
  Widget build(BuildContext context) {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _showDialog(context);
    });
    return Container();

  }
  _showDialog(context){
    return   showDialog(
      barrierDismissible: false,
      context: context,
      builder: (context) {
        return Stack(fit: StackFit.expand, children: [
          Positioned(
            top: 0,
            bottom: 0,
            left: 0,
            right: 0,
            child: Center(
              child: SpinKitDualRing(
                size: 60,
                color: Theme.of(context).accentColor,
              ),
            ),
          ),
          Positioned(
              top: 0,
              bottom: 0,
              left: 0,
              right: 0,
              child: Image.asset(
                "assets/images/cendme_logo.png",
                color: Theme.of(context).accentColor,
                scale: 7.8,
              )),
        ]);
      },
    );
  }

}
