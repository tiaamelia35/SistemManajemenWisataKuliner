import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/admin_provider.dart';

class AdminDashboardScreen extends StatefulWidget {
  const AdminDashboardScreen({Key? key}) : super(key: key);

  @override
  State<AdminDashboardScreen> createState() => _AdminDashboardScreenState();
}

class _AdminDashboardScreenState extends State<AdminDashboardScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<AdminProvider>(context, listen: false).loadSummary();
    });
  }

  @override
  Widget build(BuildContext context) {
    final prov = Provider.of<AdminProvider>(context);
    return Scaffold(
      appBar: AppBar(title: const Text('Admin Dashboard')),
      body: prov.loading
          ? const Center(child: CircularProgressIndicator())
          : prov.error != null
              ? Center(child: Text('Error: ${prov.error}'))
              : Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Ringkasan Admin', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 12),
                      Expanded(
                        child: prov.summary != null
                            ? ListView(
                                children: prov.summary!.entries.map((entry) {
                                  return Card(
                                    margin: const EdgeInsets.only(bottom: 12),
                                    child: ListTile(
                                      title: Text(entry.key.toString()),
                                      subtitle: Text(entry.value.toString()),
                                    ),
                                  );
                                }).toList(),
                              )
                            : const Center(child: Text('Tidak ada data ringkasan')),
                      ),
                      const SizedBox(height: 8),
                      ElevatedButton(onPressed: () => Navigator.pushNamed(context, '/admin/users'), child: const Text('Kelola Pengguna')),
                      const SizedBox(height: 8),
                      ElevatedButton(onPressed: () => Navigator.pushNamed(context, '/admin/reports'), child: const Text('Laporan & Grafik')),
                    ],
                  ),
                ),
    );
  }
}
