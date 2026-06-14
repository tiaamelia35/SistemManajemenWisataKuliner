import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../services/api_service.dart';
import '../models/restaurant.dart';
import '../providers/booking_provider.dart';

class DetailScreen extends StatefulWidget {
  const DetailScreen({Key? key}) : super(key: key);

  @override
  State<DetailScreen> createState() => _DetailScreenState();
}

class _DetailScreenState extends State<DetailScreen> {
  late int restaurantId;
  Restaurant? restaurant;
  bool loading = true;
  String? error;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    final args = ModalRoute.of(context)!.settings.arguments;
    restaurantId = (args is int) ? args : int.parse('${args}');
    _load();
  }

  Future<void> _load() async {
    setState(() {
      loading = true;
      error = null;
    });
    try {
      restaurant = await ApiService.fetchRestaurantDetail(restaurantId);
    } catch (e) {
      error = e.toString();
    } finally {
      setState(() {
        loading = false;
      });
    }
  }

  void _openBookingDialog() {
    final dateCtrl = TextEditingController();
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text('Buat Booking'),
        content: TextField(
          controller: dateCtrl,
          decoration: InputDecoration(labelText: 'Tanggal (YYYY-MM-DD)'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: Text('Batal')),
          ElevatedButton(
              onPressed: () async {
                final payload = {'restaurant_id': restaurantId, 'date': dateCtrl.text};
                Navigator.pop(context);
                final prov = Provider.of<BookingProvider>(context, listen: false);
                await prov.createBooking(payload);
                if (prov.error != null) {
                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: \\${prov.error}')));
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Booking dibuat')));
                }
              },
              child: Text('Booking'))
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Detail')),
      body: loading
          ? Center(child: CircularProgressIndicator())
          : error != null
              ? Center(child: Text('Error: \\$error'))
              : SingleChildScrollView(
                  padding: EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (restaurant?.imageUrl != null)
                        Image.network(restaurant!.imageUrl!),
                      SizedBox(height: 12),
                      Text(restaurant?.name ?? '-', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                      SizedBox(height: 8),
                      Text('Rating: \\${restaurant?.rating ?? 0}'),
                      SizedBox(height: 12),
                      Text(restaurant?.description ?? ''),
                      SizedBox(height: 20),
                      Center(
                        child: ElevatedButton(
                          onPressed: _openBookingDialog,
                          child: Text('Book Sekarang'),
                        ),
                      )
                    ],
                  ),
                ),
    );
  }
}
