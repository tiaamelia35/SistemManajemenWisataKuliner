import 'package:flutter/material.dart';
import 'package:webview_flutter/webview_flutter.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'SMWKP Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.red),
      ),
      home: const WebViewScreen(),
    );
  }
}

class WebViewScreen extends StatefulWidget {
  const WebViewScreen({super.key});

  @override
  State<WebViewScreen> createState() => _WebViewScreenState();
}

class _WebViewScreenState extends State<WebViewScreen> {
  late final WebViewController controller;

  @override
  void initState() {
    super.initState();
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      // Gunakan 10.0.2.2 untuk Emulator Android.
      // Jika pakai HP Fisik, ganti dengan IP lokal komputermu (misal: 192.168.1.10:8000)
      // Ganti 192.168.1.15 dengan IP IPv4 komputermu yang asli
      ..loadRequest(Uri.parse('http://10.0.2.2:8000/login'));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // SafeArea mencegah tampilan web tertutup poni/notch layar HP
      body: SafeArea(
        child: WebViewWidget(controller: controller),
      ),
    );
  }
}