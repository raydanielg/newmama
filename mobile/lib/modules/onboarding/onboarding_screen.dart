import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:animate_do/animate_do.dart';
import 'package:get/get.dart';
import '../../core/constants/app_colors.dart';
import '../../core/routes/app_routes.dart';

class OnboardingController extends GetxController {
  var selectedStatus = ''.obs;
  var isLoading = false.obs;

  void setStatus(String status) {
    selectedStatus.value = status;
  }

  void completeOnboarding() async {
    if (selectedStatus.isEmpty) return;
    
    isLoading.value = true;
    // Simulate API call to save status
    await Future.delayed(const Duration(seconds: 1));
    isLoading.value = false;
    
    Get.offAllNamed(AppRoutes.dashboard);
  }
}

class OnboardingScreen extends StatelessWidget {
  const OnboardingScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final controller = Get.put(OnboardingController());

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: Padding(
          padding: EdgeInsets.all(24.w),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(height: 20.h),
              FadeInDown(
                child: Text(
                  'Boresha Safari Yako',
                  style: TextStyle(
                    fontSize: 28.sp,
                    fontWeight: FontWeight.w900,
                    color: AppColors.textPrimary,
                    letterSpacing: -0.5,
                  ),
                ),
              ),
              SizedBox(height: 8.h),
              FadeInDown(
                delay: const Duration(milliseconds: 200),
                child: Text(
                  'Tusaidie kukupa huduma bora zaidi kwa kuchagua hali yako ya sasa.',
                  style: TextStyle(
                    fontSize: 15.sp,
                    color: AppColors.textSecondary,
                    height: 1.5,
                  ),
                ),
              ),
              SizedBox(height: 40.h),
              Expanded(
                child: Column(
                  children: [
                    _buildOption(
                      id: 'pregnant',
                      title: 'Nina Ujauzito',
                      desc: 'Kufuatilia maendeleo ya mimba na afya ya mtoto.',
                      icon: Icons.pregnant_woman,
                      color: const Color(0xFFFDF2F8),
                      iconColor: const Color(0xFFD63384),
                      controller: controller,
                    ),
                    SizedBox(height: 16.h),
                    _buildOption(
                      id: 'new_parent',
                      title: 'Tayari nina Mtoto',
                      desc: 'Malezi, chanjo, na afya ya baada ya uzazi.',
                      icon: Icons.child_care,
                      color: const Color(0xFFEEF2FF),
                      iconColor: const Color(0xFF1E40AF),
                      controller: controller,
                    ),
                    SizedBox(height: 16.h),
                    _buildOption(
                      id: 'trying',
                      title: 'Natamani Kupata Mtoto',
                      desc: 'Ushauri wa afya na maandalizi ya uzazi.',
                      icon: Icons.favorite_border,
                      color: const Color(0xFFF0FDF4),
                      iconColor: const Color(0xFF16A34A),
                      controller: controller,
                    ),
                  ],
                ),
              ),
              Obx(() => FadeInUp(
                child: SizedBox(
                  width: double.infinity,
                  height: 56.h,
                  child: ElevatedButton(
                    onPressed: controller.selectedStatus.isEmpty || controller.isLoading.value
                        ? null
                        : controller.completeOnboarding,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.textPrimary,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(18.r),
                      ),
                    ),
                    child: controller.isLoading.value
                        ? const CircularProgressIndicator(color: Colors.white)
                        : Text(
                            'Kamilisha',
                            style: TextStyle(
                              fontSize: 16.sp,
                              fontWeight: FontWeight.w800,
                            ),
                          ),
                  ),
                ),
              )),
              SizedBox(height: 20.h),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildOption({
    required String id,
    required String title,
    required String desc,
    required IconData icon,
    required Color color,
    required Color iconColor,
    required OnboardingController controller,
  }) {
    return Obx(() {
      final isSelected = controller.selectedStatus.value == id;
      return GestureDetector(
        onTap: () => controller.setStatus(id),
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 200),
          padding: EdgeInsets.all(20.w),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(24.r),
            border: Border.all(
              color: isSelected ? iconColor : Colors.white,
              width: 2,
            ),
            boxShadow: [
              BoxShadow(
                color: isSelected 
                    ? iconColor.withOpacity(0.1) 
                    : Colors.black.withOpacity(0.02),
                blurRadius: 20,
                offset: const Offset(0, 10),
              ),
            ],
          ),
          child: Row(
            children: [
              Container(
                width: 56.w,
                height: 56.h,
                decoration: BoxDecoration(
                  color: color,
                  borderRadius: BorderRadius.circular(16.r),
                ),
                child: Icon(icon, color: iconColor, size: 28.sp),
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
                    SizedBox(height: 4.h),
                    Text(
                      desc,
                      style: TextStyle(
                        fontSize: 13.sp,
                        color: AppColors.textSecondary,
                        fontWeight: FontWeight.w500,
                        height: 1.4,
                      ),
                    ),
                  ],
                ),
              ),
              if (isSelected)
                Icon(Icons.check_circle, color: iconColor, size: 24.sp),
            ],
          ),
        ),
      );
    });
  }
}
