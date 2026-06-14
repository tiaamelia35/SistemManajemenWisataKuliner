import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/admin_provider.dart';

class AdminUsersScreen extends StatefulWidget {
  const AdminUsersScreen({Key? key}) : super(key: key);

  @override
  State<AdminUsersScreen> createState() => _AdminUsersScreenState();
}

class _AdminUsersScreenState extends State<AdminUsersScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<AdminProvider>(context, listen: false).loadUsers();
    });
  }

  @override
  Widget build(BuildContext context) {
    final prov = Provider.of<AdminProvider>(context);
    return Scaffold(
      appBar: AppBar(title: const Text('Manajemen Pengguna')),
      body: prov.loading
          ? const Center(child: CircularProgressIndicator())
          : prov.error != null
              ? Center(child: Text('Error: ${prov.error}'))
              : prov.users.isEmpty
                  ? const Center(child: Text('Belum ada pengguna'))
                  : ListView.separated(
                      padding: const EdgeInsets.all(16.0),
                      itemCount: prov.users.length,
                      separatorBuilder: (_, __) => const SizedBox(height: 12),
                      itemBuilder: (context, i) {
                        final u = prov.users[i];
                        return Card(
                          child: ListTile(
                            title: Text(u['name'] ?? u['email'] ?? 'Pengguna'),
                            subtitle: Text('Role: ${u['role'] ?? '-'}\n${u['email'] ?? ''}'),
                            isThreeLine: true,
                            trailing: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                IconButton(
                                  icon: const Icon(Icons.refresh),
                                  onPressed: () async {
                                    final ok = await prov.updateUser(u['id'], {'role': u['role']});
                                    if (!ok) {
                                      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Update gagal: ${prov.error}')));
                                    }
                                  },
                                ),
                                IconButton(
                                  icon: const Icon(Icons.delete),
                                  onPressed: () async {
                                    final ok = await prov.deleteUser(u['id']);
                                    if (!ok) {
                                      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Hapus gagal: ${prov.error}')));
                                    }
                                  },
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
    );
  }
}
