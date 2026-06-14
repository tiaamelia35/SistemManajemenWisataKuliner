import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../providers/profile_provider.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({Key? key}) : super(key: key);

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<ProfileProvider>(context, listen: false).loadProfile();
    });
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _emailCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final prov = Provider.of<ProfileProvider>(context);
    final user = prov.user;
    if (user != null) {
      _nameCtrl.text = user.name;
      _emailCtrl.text = user.email;
    }

    return Scaffold(
      appBar: AppBar(title: const Text('Profil')),
      body: prov.loading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 46,
                    backgroundImage: user?.avatarUrl != null ? NetworkImage(user!.avatarUrl!) : null,
                    child: user?.avatarUrl == null ? const Icon(Icons.person, size: 48) : null,
                  ),
                  const SizedBox(height: 14),
                  TextButton(
                    onPressed: () async {
                      final picker = ImagePicker();
                      final img = await picker.pickImage(source: ImageSource.gallery, imageQuality: 80);
                      if (img != null) {
                        await prov.uploadAvatar(img.path);
                        if (prov.error != null) {
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Upload gagal: ${prov.error}')));
                        }
                      }
                    },
                    child: const Text('Ubah Avatar'),
                  ),
                  const SizedBox(height: 20),
                  TextField(controller: _nameCtrl, decoration: const InputDecoration(labelText: 'Nama')),
                  const SizedBox(height: 12),
                  TextField(controller: _emailCtrl, decoration: const InputDecoration(labelText: 'Email')),
                  const SizedBox(height: 24),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () async {
                        final ok = await prov.updateProfile({
                          'name': _nameCtrl.text.trim(),
                          'email': _emailCtrl.text.trim(),
                        });
                        if (!ok) {
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Update gagal: ${prov.error}')));
                        }
                      },
                      child: const Text('Simpan Perubahan'),
                    ),
                  ),
                ],
              ),
            ),
    );
  }
}
