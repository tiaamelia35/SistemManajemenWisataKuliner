import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../providers/owner_menu_provider.dart';
import '../models/menu.dart';

class OwnerMenuScreen extends StatefulWidget {
  const OwnerMenuScreen({Key? key}) : super(key: key);

  @override
  State<OwnerMenuScreen> createState() => _OwnerMenuScreenState();
}

class _OwnerMenuScreenState extends State<OwnerMenuScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<OwnerMenuProvider>(context, listen: false).fetchMenus();
    });
  }

  Future<void> _openCreateDialog() async {
    final nameCtrl = TextEditingController();
    final priceCtrl = TextEditingController();
    final descCtrl = TextEditingController();
    String? imagePath;
    final picker = ImagePicker();

    await showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text('Tambah Menu'),
        content: SingleChildScrollView(
          child: Column(
            children: [
              TextField(controller: nameCtrl, decoration: InputDecoration(labelText: 'Name')),
              TextField(controller: priceCtrl, decoration: InputDecoration(labelText: 'Price')),
              TextField(controller: descCtrl, decoration: InputDecoration(labelText: 'Description')),
              SizedBox(height: 8),
              ElevatedButton(
                onPressed: () async {
                  final img = await picker.pickImage(source: ImageSource.gallery, imageQuality: 80);
                  if (img != null) imagePath = img.path;
                },
                child: Text('Pick Image'),
              )
            ],
          ),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: Text('Batal')),
          ElevatedButton(
            onPressed: () async {
              final payload = {'name': nameCtrl.text.trim(), 'price': priceCtrl.text.trim(), 'description': descCtrl.text.trim()};
              Navigator.pop(context);
              final prov = Provider.of<OwnerMenuProvider>(context, listen: false);
              final ok = await prov.createMenu(payload, imagePath: imagePath);
              if (!ok) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Create failed: \\${prov.error}')));
            },
            child: Text('Simpan'),
          )
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final prov = Provider.of<OwnerMenuProvider>(context);
    return Scaffold(
      appBar: AppBar(title: Text('Menu Management')),
      body: prov.loading
          ? Center(child: CircularProgressIndicator())
          : prov.error != null
              ? Center(child: Text('Error: \\${prov.error}'))
              : ListView.builder(
                  itemCount: prov.menus.length,
                  itemBuilder: (context, i) {
                    final m = prov.menus[i];
                    return ListTile(
                      leading: m.imageUrl != null ? Image.network(m.imageUrl!, width: 56, height: 56, fit: BoxFit.cover) : Icon(Icons.fastfood),
                      title: Text(m.name),
                      subtitle: Text('Price: \\${m.price ?? '-'}'),
                      trailing: Row(mainAxisSize: MainAxisSize.min, children: [
                        IconButton(
                          icon: Icon(Icons.edit),
                          onPressed: () {},
                        ),
                        IconButton(
                          icon: Icon(Icons.delete),
                          onPressed: () async {
                            final ok = await prov.deleteMenu(m.id);
                            if (!ok) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Delete failed: \\${prov.error}')));
                          },
                        ),
                      ]),
                    );
                  },
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: _openCreateDialog,
        child: Icon(Icons.add),
      ),
    );
  }
}
