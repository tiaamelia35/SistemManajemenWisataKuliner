SMWKP Flutter WebView wrapper

This folder contains a Flutter wrapper that loads the existing Laravel website directly in a WebView so the app preserves the website UI and workflow without changing the backend.

Quick start (requires Flutter SDK):

```bash
cd flutter_app
flutter pub get
flutter run
```

By default, the WebView loads `http://10.0.2.2:8000` for local Android emulator development.

To use a different URL, update `lib/screens/webview_screen.dart` and set `initialUrl` to your Laravel site address.
