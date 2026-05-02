import 'package:url_launcher/url_launcher.dart';
import 'package:get/get.dart';
import '../core/constants/app_constants.dart';

class ChatService extends GetxService {
  // WhatsApp Integration
  Future<void> launchWhatsApp({String? message}) async {
    final phone = AppConstants.whatsappNumber;
    final msg = message ?? "Habari Mamacare AI, naomba msaada wa...";
    final url = "https://wa.me/$phone?text=${Uri.encodeComponent(msg)}";
    
    if (await canLaunchUrl(Uri.parse(url))) {
      await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
    } else {
      Get.snackbar(
        "Error",
        "WhatsApp haijawekwa kwenye simu yako",
        snackPosition: SnackPosition.BOTTOM,
      );
    }
  }

  // Internal AI Chat (Placeholder for API based chat)
  Future<void> sendInternalMessage(String text) async {
    // Logic for internal chat API
  }
}
