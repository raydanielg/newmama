import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:animate_do/animate_do.dart';
import 'package:get/get.dart';
import '../../core/constants/app_colors.dart';
import '../../core/routes/app_routes.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          _buildSliverAppBar(),
          SliverToBoxAdapter(
            child: Padding(
              padding: EdgeInsets.all(24.w),
              child: Column(
                children: [
                  FadeInUp(child: _buildProfileInfo()),
                  SizedBox(height: 32.h),
                  FadeInUp(delay: const Duration(milliseconds: 200), child: _buildStatsRow()),
                  SizedBox(height: 32.h),
                  FadeInUp(delay: const Duration(milliseconds: 400), child: _buildMenuSection()),
                  SizedBox(height: 32.h),
                  FadeInUp(
                    delay: const Duration(milliseconds: 600),
                    child: _buildLogoutButton(),
                  ),
                  SizedBox(height: 40.h),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSliverAppBar() {
    return SliverAppBar(
      expandedHeight: 120.h,
      floating: false,
      pinned: true,
      backgroundColor: Colors.white,
      elevation: 0,
      flexibleSpace: FlexibleSpaceBar(
        centerTitle: true,
        title: Text(
          'Akaunti Yako',
          style: TextStyle(
            color: AppColors.textPrimary,
            fontWeight: FontWeight.w900,
            fontSize: 18.sp,
          ),
        ),
      ),
    );
  }

  Widget _buildProfileInfo() {
    return Column(
      children: [
        Stack(
          children: [
            Container(
              width: 120.w,
              height: 120.h,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                border: Border.all(color: AppColors.primary, width: 3),
                image: const DecorationImage(
                  image: NetworkImage('https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400'),
                  fit: BoxFit.cover,
                ),
              ),
            ),
            Positioned(
              bottom: 0,
              right: 0,
              child: Container(
                padding: EdgeInsets.all(8.w),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  shape: BoxShape.circle,
                ),
                child: Icon(Icons.edit, color: Colors.white, size: 18.sp),
              ),
            ),
          ],
        ),
        SizedBox(height: 16.h),
        Text(
          'Mama Sarah Johnson',
          style: TextStyle(fontSize: 22.sp, fontWeight: FontWeight.w900, color: AppColors.textPrimary),
        ),
        Text(
          'sarah@mamacare.co.tz',
          style: TextStyle(fontSize: 14.sp, fontWeight: FontWeight.w500, color: AppColors.textSecondary),
        ),
      ],
    );
  }

  Widget _buildStatsRow() {
    return Container(
      padding: EdgeInsets.all(20.w),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24.r),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 20, offset: const Offset(0, 10)),
        ],
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: [
          _buildStatColumn('Wiki', '24'),
          _buildStatColumn('Vipimo', '156'),
          _buildStatColumn('Makala', '42'),
        ],
      ),
    );
  }

  Widget _buildStatColumn(String label, String value) {
    return Column(
      children: [
        Text(value, style: TextStyle(fontSize: 20.sp, fontWeight: FontWeight.w900, color: AppColors.primary)),
        Text(label, style: TextStyle(fontSize: 12.sp, fontWeight: FontWeight.w600, color: AppColors.textSecondary)),
      ],
    );
  }

  Widget _buildMenuSection() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24.r),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 20, offset: const Offset(0, 10)),
        ],
      ),
      child: Column(
        children: [
          _buildMenuItem(Icons.person_outline_rounded, 'Hariri Profile', 'Badili jina na picha', () => Get.toNamed(AppRoutes.editProfile)),
          _buildDivider(),
          _buildMenuItem(Icons.notifications_none_rounded, 'Taarifa', 'Mpangilio wa ujumbe', () => Get.toNamed(AppRoutes.notifications)),
          _buildDivider(),
          _buildMenuItem(Icons.lock_outline_rounded, 'Usalama', 'Badili neno la siri', () {}),
          _buildDivider(),
          _buildMenuItem(Icons.help_outline_rounded, 'Msaada', 'Maswali na Majibu', () => Get.toNamed(AppRoutes.helpSupport)),
        ],
      ),
    );
  }

  Widget _buildMenuItem(IconData icon, String title, String subtitle, VoidCallback onTap) {
    return ListTile(
      onTap: onTap,
      contentPadding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 8.h),
      leading: Container(
        padding: EdgeInsets.all(10.w),
        decoration: BoxDecoration(color: AppColors.surfaceVariant, borderRadius: BorderRadius.circular(12.r)),
        child: Icon(icon, color: AppColors.primary, size: 24.sp),
      ),
      title: Text(title, style: TextStyle(fontSize: 16.sp, fontWeight: FontWeight.w800, color: AppColors.textPrimary)),
      subtitle: Text(subtitle, style: TextStyle(fontSize: 12.sp, color: AppColors.textSecondary)),
      trailing: Icon(Icons.arrow_forward_ios_rounded, color: AppColors.textTertiary, size: 14.sp),
    );
  }

  Widget _buildDivider() {
    return Divider(height: 1, thickness: 1, color: AppColors.borderLight, indent: 70.w);
  }

  Widget _buildLogoutButton() {
    return SizedBox(
      width: double.infinity,
      child: TextButton(
        onPressed: () => Get.offAllNamed(AppRoutes.login),
        style: TextButton.styleFrom(
          padding: EdgeInsets.symmetric(vertical: 16.h),
          foregroundColor: AppColors.error,
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.logout_rounded),
            SizedBox(width: 8.w),
            Text('Toka kwenye App', style: TextStyle(fontSize: 16.sp, fontWeight: FontWeight.w800)),
          ],
        ),
      ),
    );
  }
}
