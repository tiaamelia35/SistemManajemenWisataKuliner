import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/admin_provider.dart';

class AdminReportsScreen extends StatefulWidget {
  const AdminReportsScreen({Key? key}) : super(key: key);

  @override
  State<AdminReportsScreen> createState() => _AdminReportsScreenState();
}

class _AdminReportsScreenState extends State<AdminReportsScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<AdminProvider>(context, listen: false).loadReports();
    });
  }

  @override
  Widget build(BuildContext context) {
    final prov = Provider.of<AdminProvider>(context);
    return Scaffold(
      appBar: AppBar(title: const Text('Laporan & Grafik')),
      body: prov.loading
          ? const Center(child: CircularProgressIndicator())
          : prov.error != null
              ? Center(child: Text('Error: ${prov.error}'))
              : prov.reports == null
                  ? const Center(child: Text('Tidak ada laporan'))
                  : Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: ListView(
                        children: prov.reports!.entries.map((entry) {
                          return Card(
                            margin: const EdgeInsets.only(bottom: 12),
                            child: ListTile(
                              title: Text(entry.key.toString()),
                              subtitle: Text(entry.value.toString()),
                            ),
                          );
                        }).toList(),
                      ),
                    ),
    );
  }
}
