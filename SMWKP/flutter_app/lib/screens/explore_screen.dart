import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/restaurant_provider.dart';

class ExploreScreen extends StatefulWidget {
  const ExploreScreen({Key? key}) : super(key: key);

  @override
  State<ExploreScreen> createState() => _ExploreScreenState();
}

class _ExploreScreenState extends State<ExploreScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<RestaurantProvider>(context, listen: false).fetchRestaurants();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Jelajah')),
      body: Consumer<RestaurantProvider>(builder: (context, prov, _) {
        if (prov.loading) return Center(child: CircularProgressIndicator());
        if (prov.error != null) return Center(child: Text('Error: \\${prov.error}'));
        if (prov.restaurants.isEmpty) return Center(child: Text('Tidak ada restoran'));
        return ListView.builder(
          itemCount: prov.restaurants.length,
          itemBuilder: (context, i) {
            final r = prov.restaurants[i];
            return ListTile(
              leading: r.imageUrl != null
                  ? Image.network(r.imageUrl!, width: 56, height: 56, fit: BoxFit.cover)
                  : SizedBox(width: 56, height: 56, child: Icon(Icons.restaurant)),
              title: Text(r.name),
              subtitle: Text('Rating: \\${r.rating}'),
              onTap: () {
                Navigator.pushNamed(context, '/detail', arguments: r.id);
              },
            );
          },
        );
      }),
    );
  }
}
