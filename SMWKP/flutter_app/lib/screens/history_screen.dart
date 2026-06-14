import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/booking_provider.dart';

class HistoryScreen extends StatefulWidget {
  const HistoryScreen({Key? key}) : super(key: key);

  @override
  State<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends State<HistoryScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<BookingProvider>(context, listen: false).fetchUserBookings();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Riwayat Booking')),
      body: Consumer<BookingProvider>(builder: (context, prov, _) {
        if (prov.loading) return Center(child: CircularProgressIndicator());
        if (prov.error != null) return Center(child: Text('Error: \\${prov.error}'));
        if (prov.bookings.isEmpty) return Center(child: Text('Belum ada booking'));
        return ListView.builder(
          itemCount: prov.bookings.length,
          itemBuilder: (context, i) {
            final b = prov.bookings[i];
            return ListTile(
              title: Text('Booking #\\${b.id} - Restoran \\${b.restaurantId}'),
              subtitle: Text('Tanggal: \\${b.date} - Status: \\${b.status}'),
            );
          },
        );
      }),
    );
  }
}
