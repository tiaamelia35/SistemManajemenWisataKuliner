import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailCtrl = TextEditingController();
  final _passCtrl = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _emailCtrl,
                decoration: InputDecoration(labelText: 'Email'),
                validator: (v) => (v == null || v.isEmpty) ? 'Required' : null,
              ),
              TextFormField(
                controller: _passCtrl,
                decoration: InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (v) => (v == null || v.isEmpty) ? 'Required' : null,
              ),
              SizedBox(height: 16),
              auth.loading ? CircularProgressIndicator() : ElevatedButton(
                onPressed: () async {
                  if (!_formKey.currentState!.validate()) return;
                  final ok = await auth.login(_emailCtrl.text.trim(), _passCtrl.text.trim());
                  if (ok) {
                    Navigator.pushReplacementNamed(context, '/explore');
                  } else {
                    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Login failed: \\${auth.error}')));
                  }
                },
                child: Text('Login'),
              )
            ],
          ),
        ),
      ),
    );
  }
}
