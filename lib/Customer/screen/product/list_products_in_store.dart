import 'package:cendme/Customer/model/product.dart';
import 'package:cendme/Customer/provider/product_provider.dart';
import 'package:cendme/Customer/screen/product/grid_product_item.dart';
import 'package:cendme/Customer/screen/product/lazy_item_loader.dart';
import 'package:cendme/Customer/screen/product/linear_product_item.dart';
import 'package:cendme/Customer/screen/product/product_category_list.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:flutter/material.dart';
import 'package:flutter/scheduler.dart';
import 'package:flutter/widgets.dart';
import 'package:fluttericon/font_awesome5_icons.dart';
import 'package:provider/provider.dart';

class ListProducts extends StatefulWidget {
  final String title;
  final String storeId;

  const ListProducts({Key key, @required this.title, @required this.storeId})
      : super(key: key);

  @override
  _ListProductsState createState() => _ListProductsState();
}

class _ListProductsState extends State<ListProducts> {
  bool isGrid = true;
  String categoryName = "";
  @override
  void initState() {
    SchedulerBinding.instance.addPostFrameCallback(
      (_) {
        Provider.of<ProductsProvider>(context, listen: false)
            .getCategories(context);
        Provider.of<ProductsProvider>(context, listen: false)
            .fetchProduct(context, widget.storeId);
      },
    );
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(body: SafeArea(child:
        Consumer<ProductsProvider>(builder: (context, productProvider, child) {
      List<Product> products = List.from(productProvider.getProductToBeShown);
      List categories = List.from(productProvider.categories);
      return Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: <Widget>[
          Container(
            height: 55,
            width: MediaQuery.of(context).size.width,
            color: Theme.of(context).primaryColor,
            child: Row(
              children: <Widget>[
                IconButton(
                    onPressed: () => Navigator.pop(context),
                    icon: Icon(
                      FontAwesome5.arrow_left,
                      color: Theme.of(context).accentColor,
                    )),
//            SizedBox(width: 10,),
                Hero(
                    tag: widget.storeId,
                    child: Text(widget.title,
                        style: Theme.of(context).textTheme.headline2.merge(
                              TextStyle(letterSpacing: 1.5),
                            ))),
              ],
            ),
          ),
          Container(
//            color: Colors.green.withOpacity(.5),
            height: 110,
            child: ListView(
              shrinkWrap: true,
              scrollDirection: Axis.horizontal,
              padding: EdgeInsets.symmetric(horizontal: 15, vertical: 10),
              children: categories.map((e) => InkWell(onTap: () {
                categoryName = e['name'];
                setState(() {});
              },child: ProductCategory(item: e,))).toList()
            ),
          ),
          Divider(
            color: Colors.grey,
            height: 1,
            thickness: 1,
          ),
          SizedBox(height: 10,),
          Container(
            margin: EdgeInsets.symmetric(horizontal: 10,),
            child: Row(
              children: <Widget>[
                Flexible(
                  child: Container(
                    padding: EdgeInsets.only(
                      left: 10,
                    ),
                    decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(30),
                        color: Theme.of(context).accentColor.withOpacity(.15)
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
                                controller: productProvider.searchController,
                                onChanged: (String value) {
//                                  Provider.of<StoreProvider>(context,
//                                      listen: false)
//                                      .searchStoreList(value);
                                  setState(() {
//                                    searchWord = value;
                                  });
                                },
                                cursorColor: Theme.of(context).accentColor,
                                decoration: InputDecoration(
                                  hintText: "search products...",
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
                                    FocusScopeNode currentFocus = FocusScope.of(context);
                                    if (!currentFocus.hasPrimaryFocus) {
                                      currentFocus.unfocus();
                                    }
                                    productProvider.searchController.clear();
                                  });

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
          ),
          SizedBox(
            height: 10,
          ),
          sortBar(context),
          Expanded(
            child: ListView(
              scrollDirection: Axis.vertical,
              padding: EdgeInsets.symmetric(horizontal: 10, vertical: 10),
              primary: true,
              physics: BouncingScrollPhysics(),
              shrinkWrap: true,
              children: <Widget>[
                if (productProvider.appState == AppState.loading)
                  if (isGrid)
                    GridView(
                        gridDelegate: SliverGridDelegateWithMaxCrossAxisExtent(
                          crossAxisSpacing: 5,
                          mainAxisSpacing: 5,
                          maxCrossAxisExtent: 300,
                          childAspectRatio: 300 / 380,
                        ),
                        padding: EdgeInsets.only(bottom: 30),
                        shrinkWrap: true,
                        physics: NeverScrollableScrollPhysics(),
                        children: [
                          LazyItemLoader(),
                          LazyItemLoader(),
                          LazyItemLoader(),
                          LazyItemLoader(),
                          LazyItemLoader(),
                          LazyItemLoader(),
                          LazyItemLoader(),
                          LazyItemLoader(),
                        ]),
                if (productProvider.appState == AppState.loading)
                  if (!isGrid)
                    ListView(
                      physics: NeverScrollableScrollPhysics(),
                      shrinkWrap: true,
                      children: <Widget>[
                        LazyItemLoader(),
                        LazyItemLoader(),
                        LazyItemLoader(),
                        LazyItemLoader(),
                        LazyItemLoader(),
                      ],
                    ),
                if (productProvider.appState == AppState.completed)
                  if (isGrid)
                    GridView(
                      gridDelegate: SliverGridDelegateWithMaxCrossAxisExtent(
                        crossAxisSpacing: 5,
                        mainAxisSpacing: 5,
                        maxCrossAxisExtent: 300,
                        childAspectRatio: MediaQuery.of(context).orientation ==
                                Orientation.portrait
                            ? 290 / 380
                            : 450 / 380,
                      ),
                      padding: EdgeInsets.only(bottom: 30),
                      shrinkWrap: true,
                      physics: NeverScrollableScrollPhysics(),
                      children: products.map((product) {
                        return GridProductItem(
                          product: product,
                        );
                      }).toList(),
                    ),
                if (productProvider.appState == AppState.completed)
                  if (!isGrid)
                    ListView(
                      physics: NeverScrollableScrollPhysics(),
                      shrinkWrap: true,
                      children: products
                          .map((product) => AspectRatio(
                                aspectRatio:
                                    MediaQuery.of(context).orientation ==
                                            Orientation.portrait
                                        ? 2.7
                                        : 5.7,
                                child: LinearProductItem(
                                  product: product,
                                ),
                              ))
                          .toList(),
                    )
              ],
            ),
          )
        ],
      );
    })));
  }

  Widget sortBar(context) {
    return Container(
      height: 55,
      color: Theme.of(context).primaryColor,
      padding: EdgeInsets.all(10),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.center,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: <Widget>[
          Row(
            children: <Widget>[
              InkWell(
                  onTap: () {
                    isGrid = false;
                    setState(() {});
                  },
                  splashColor: Theme.of(context).accentColor,
                  child: Icon(
                    Icons.view_stream,
                    color: Theme.of(context).accentColor,
                    size: 30,
                  )),
              InkWell(
                  onTap: () {
                    isGrid = true;
                    setState(() {});
                  },
                  child: Icon(
                    Icons.view_module,
                    color: Theme.of(context).accentColor,
                    size: 30,
                  )),
            ],
          ),
          categoryName == "" ?Container():
          Row(
            children: <Widget>[
              Text(
                categoryName,
                maxLines: 2,
                style: Theme.of(context).textTheme.subtitle1,
                textAlign: TextAlign.center,
              ),
              SizedBox(width: 10,),
              InkWell(
                  onTap: () {},
                  child: Icon(Icons.tune,
                      color: Theme.of(context).accentColor, size: 30)),
            ],
          )
        ],
      ),
    );
  }
}
