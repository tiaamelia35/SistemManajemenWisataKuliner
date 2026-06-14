import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/restaurant_provider.dart';
import 'providers/booking_provider.dart';
import 'providers/auth_provider.dart';
import 'providers/admin_provider.dart';
import 'providers/profile_provider.dart';
import 'providers/owner_menu_provider.dart';
import 'screens/webview_screen.dart';
import 'screens/explore_screen.dart';
import 'screens/detail_screen.dart';
import 'screens/login_screen.dart';
import 'screens/history_screen.dart';
import 'screens/profile_screen.dart';
import 'screens/owner_menu_screen.dart';
import 'screens/owner_dashboard_screen.dart';
import 'screens/admin_dashboard_screen.dart';
import 'screens/admin_users_screen.dart';
import 'screens/admin_reports_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => RestaurantProvider()),
        ChangeNotifierProvider(create: (_) => BookingProvider()),
        ChangeNotifierProvider(create: (_) => ProfileProvider()),
        ChangeNotifierProvider(create: (_) => OwnerMenuProvider()),
        ChangeNotifierProvider(create: (_) => AdminProvider()),
      ],
      child: MaterialApp(
        title: 'SMWKP Mobile',
        theme: ThemeData(
          primarySwatch: Colors.teal,
          visualDensity: VisualDensity.adaptivePlatformDensity,
        ),
        initialRoute: '/login',
        routes: {
          '/': (context) => LoginScreen(),
          '/login': (context) => LoginScreen(),
          '/explore': (context) => ProtectedRoute(child: ExploreScreen()),
          '/detail': (context) => ProtectedRoute(child: DetailScreen()),
          '/booking': (context) => ProtectedRoute(child: Scaffold(appBar: AppBar(title: Text('Booking')), body: Center(child: Text('Booking screen')))),
          '/history': (context) => ProtectedRoute(child: HistoryScreen()),
          '/profile': (context) => ProtectedRoute(child: ProfileScreen()),
          '/owner/dashboard': (context) => ProtectedRoute(child: OwnerDashboardScreen()),
          '/owner/menu': (context) => ProtectedRoute(child: OwnerMenuScreen()),
          '/admin/dashboard': (context) => ProtectedRoute(child: AdminDashboardScreen()),
          '/admin/users': (context) => ProtectedRoute(child: AdminUsersScreen()),
          '/admin/reports': (context) => ProtectedRoute(child: AdminReportsScreen()),
          '/web': (context) => WebViewScreen(),
        },
      ),
    );
  }
}

class ProtectedRoute extends StatelessWidget {
  final Widget child;
  const ProtectedRoute({required this.child, Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    if (auth.loading) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    if (!auth.isAuthenticated) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        Navigator.pushReplacementNamed(context, '/login');
      });
      return const SizedBox.shrink();
    }
    return child;
  }
}
