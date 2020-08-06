import 'dart:math';

import 'package:cendme/Customer/model/store.dart';
import 'package:cendme/Customer/provider/stores_provider.dart';
import 'package:cendme/Customer/screen/home/storeCardWidget.dart';
import 'package:cendme/component/ApiErrorWidget.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:cendme/widget/loadingWidget.dart';
import 'package:flutter/material.dart';
import 'package:flutter/scheduler.dart';
import 'package:fluttericon/elusive_icons.dart';
import 'package:provider/provider.dart';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  bool showSearchField = false;
  TextEditingController textController = TextEditingController();
  String searchWord = '';

  @override
  void initState() {
    SchedulerBinding.instance.addPostFrameCallback(
      (_) {
        Provider.of<StoreProvider>(context, listen: false).fetchStores(context);
      },
    );
    super.initState();
  }

  Future<bool> _willPop() async {
    bool res = true;
    if (showSearchField) {
      Provider.of<StoreProvider>(context, listen: false).clearSearchResult();
      res = false;
      setState(() => showSearchField = false);
    }
    return res;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        centerTitle: false,
        elevation: 0,
        title: showSearchField
            ? Container(
                child: Row(
                  children: <Widget>[
                    Flexible(
                      child: Container(
                        margin: EdgeInsets.symmetric(
                          vertical: 10,
                        ),
                        padding: EdgeInsets.only(
                          left: 10,
                        ),
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(30),
                          color: Theme.of(context).accentColor.withOpacity(.5)
                        ),
                        child: Center(
                          child: Container(
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: <Widget>[
                                Container(
                                  margin: EdgeInsets.only(
                                    right: 5,
                                  ),
                                  child: Icon(
                                    Icons.search,
                                    color: Theme.of(context).accentColor,
                                    size: 18,
                                  ),
                                ),
                                Flexible(
                                  child: TextField(
                                    controller: textController,
                                    onChanged: (String value) {
                                      Provider.of<StoreProvider>(context,
                                              listen: false)
                                          .searchStoreList(value);
                                      setState(() {
                                        searchWord = value;
                                      });
                                    },
                                    cursorColor: Theme.of(context).accentColor,
                                    decoration: InputDecoration(
                                      hintText: "search...",
                                      hintStyle: TextStyle(
                                        color: Theme.of(context).accentColor,
                                        fontSize: 12

                                      ),
                                      border: InputBorder.none,
                                    ),
                                  ),
                                ),
                                  Container(
                                    alignment: Alignment.center,
                                    child: IconButton(
                                      icon: Icon(
                                        Icons.cancel,
                                        color: Theme.of(context).accentColor,
                                        size: 18,
                                      ),
                                      onPressed: () {
                                        setState(() {
                                          textController.clear();
                                          searchWord = '';
                                        });
                                        _willPop();
                                        Provider.of<StoreProvider>(context,
                                                listen: false)
                                            .clearSearchResult();
                                      },
                                    ),
                                  )
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              )
            : Text(
                "CendMe",
          style: Theme.of(context).textTheme.headline2.merge(TextStyle(letterSpacing: 1.5)),
              ),
        actions: <Widget>[
          if (!showSearchField)
            Consumer<StoreProvider>(
              builder: (context, mod, child) => IconButton(
                onPressed: () => mod.storesToBeShown.length > 0
                    ? setState(() => showSearchField = true)
                    : null,
                icon: Icon(
                  Elusive.search_circled,
                  color: Theme.of(context).accentColor,
                ),
              ),
            ),
        ],
      ),
      body: Consumer<StoreProvider>(
        builder: (context, mod, child) {
          List<Store> recentStores = List.from(mod.storesToBeShown);
//          recentStores.shuffle(Random(DateTime.now().millisecondsSinceEpoch));
          switch (mod.appState) {
            case AppState.initialized:
              return Container();
            case AppState.error:
              return Center(
                child: ApiErrorWidget(
                  onPressed: () {
                    SchedulerBinding.instance.addPostFrameCallback((_) =>
                        Provider.of<StoreProvider>(context, listen: false)
                            .fetchStores(context));
                  },
                  text: "Erorr fatching stores...",
                ),
              );
            case AppState.connectionError:
              return Center(
                child: ApiErrorWidget(
                  onPressed: () {
                    SchedulerBinding.instance.addPostFrameCallback((_) =>
                        Provider.of<StoreProvider>(context, listen: false)
                            .fetchStores(context));
                  },
                  text: "Bad internet connection...",
                ),
              );
              break;
            case AppState.loading:
              return LoadingWidget();
              break;
            case AppState.completed:
              return mod.storesToBeShown.length > 0
                  ? TweenAnimationBuilder(
                builder: (context, value, child) {
                  return Opacity(
                    opacity: value,
                    child: child,
                  );
                },duration: Duration(seconds: 1),
                tween: Tween<double>(begin: 0.1,end:1.0),
                    child: ListView(
                        padding: EdgeInsets.symmetric(horizontal: 20),
                        children: <Widget>[
                          Container(
                            margin: EdgeInsets.symmetric(vertical: 20),
                            child: Text(
                              "Stores near you".toUpperCase(),
                              style: TextStyle(
                                color: Colors.green,
                                fontSize: 15,
                                fontWeight: FontWeight.w800,
                              ),
                            ),
                          ),
                          GridView(
                            gridDelegate:
                                SliverGridDelegateWithMaxCrossAxisExtent(
                              crossAxisSpacing: 10,
                              mainAxisSpacing: 10,
                              maxCrossAxisExtent: 300,
                              childAspectRatio:  MediaQuery.of(context).orientation == Orientation.portrait ? 180 / 280 : 280/280,
                            ),
                              padding: EdgeInsets.only(bottom: 30),
                            shrinkWrap: true,
                            physics: NeverScrollableScrollPhysics(),
                            children: recentStores
                                .map((each) => StoreCardWidget(
                                      store: each,
                                    ))
                                .toList(),
                          ),
                        ],
                      ),
                  )
                  : Center(
                      child: Text(showSearchField
                          ? "No result found"
                          : "No store around you"),
                    );
          }
          return null;
        },
      ),
    );
  }
}
