import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:animate_do/animate_do.dart';
import 'package:get/get.dart';
import '../../core/constants/app_colors.dart';
import '../../services/chat_service.dart';

class ChatScreen extends StatelessWidget {
  const ChatScreen({super.key});

  @override
  Widget build(BuildContext context) {
    // Only put the service if it's not already there
    final ChatService chatService = Get.isRegistered<ChatService>() 
        ? Get.find<ChatService>() 
        : Get.put(ChatService());

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Mamacare AI Chat'),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
      ),
      body: Padding(
        padding: EdgeInsets.all(24.w),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            FadeInDown(
              child: Container(
                padding: EdgeInsets.all(20.w),
                decoration: BoxDecoration(
                  color: AppColors.primary50,
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  Icons.chat_bubble_rounded,
                  color: AppColors.primary,
                  size: 64.sp,
                ),
              ),
            ),
            SizedBox(height: 32.h),
            FadeInUp(
              child: Text(
                'Ongea na AI wetu sasa',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 24.sp,
                  fontWeight: FontWeight.w900,
                  color: AppColors.textPrimary,
                ),
              ),
            ),
            SizedBox(height: 16.h),
            FadeInUp(
              delay: const Duration(milliseconds: 200),
              child: Text(
                'Pata majibu ya haraka kuhusu afya yako, lishe, na maendeleo ya mtoto kupitia WhatsApp au mfumo wetu.',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 16.sp,
                  color: AppColors.textSecondary,
                  height: 1.5,
                ),
              ),
            ),
            SizedBox(height: 48.h),
            FadeInUp(
              delay: const Duration(milliseconds: 400),
              child: _buildChatOption(
                title: 'Ongea kupitia WhatsApp',
                subtitle: 'Muda wowote, masaa 24/7',
                icon: Icons.whatsapp,
                color: const Color(0xFF25D366),
                onTap: () => chatService.launchWhatsApp(),
              ),
            ),
            SizedBox(height: 16.h),
            FadeInUp(
              delay: const Duration(milliseconds: 600),
              child: _buildChatOption(
                title: 'Chat ya Ndani (App)',
                subtitle: 'Itafunguliwa hivi karibuni',
                icon: Icons.bolt_rounded,
                color: AppColors.primary,
                onTap: () {
                  Get.snackbar("Taarifa", "Feature hii inatengenezwa. Tafadhali tumia WhatsApp.");
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildChatOption({
    required String title,
    required String subtitle,
    required IconData icon,
    required Color color,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: EdgeInsets.all(20.w),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24.r),
          boxShadow: [
            BoxShadow(
              color: color.withOpacity(0.1),
              blurRadius: 20,
              offset: const Offset(0, 8),
            ),
          ],
          border: Border.all(color: color.withOpacity(0.1)),
        ),
        child: Row(
          children: [
            Container(
              padding: EdgeInsets.all(12.w),
              decoration: BoxDecoration(
                color: color.withOpacity(0.1),
                borderRadius: BorderRadius.circular(16.r),
              ),
              child: Icon(icon, color: color, size: 28.sp),
            ),
            SizedBox(width: 20.w),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: TextStyle(
                      fontSize: 17.sp,
                      fontWeight: FontWeight.w900,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  Text(
                    subtitle,
                    style: TextStyle(
                      fontSize: 13.sp,
                      color: AppColors.textSecondary,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              ),
            ),
            Icon(Icons.arrow_forward_ios_rounded, color: AppColors.textTertiary, size: 16.sp),
          ],
        ),
      ),
    );
  }
}
